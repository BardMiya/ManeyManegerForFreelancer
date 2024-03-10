import { reactive, computed } from 'vue'
import { store } from '@/store/index'
import { i18n } from '@/plugin/i18n'

export function useRestAccessor(){

    const baseUri = import.meta.env.VITE_API_BASE_URL
    const postType = ['PUT', 'POST', 'DELETE', 'PATCH']
    const arrowdMethods = ['GET','HEAD','OPTION','PUT', 'POST', 'DELETE', 'PATCH']
    const t = i18n.global.t
    /**
     * URLリクエスト
     * @param {String} path 
     * @param {String} method 
     * @param {Object} parameter 
     * @param {Object} header 
     * @returns Object 
     */
    async function request(path, method, parameter = {}, header = {}){
        if(!arrowdMethods.some(m => m == method.toUpperCase())){
            throw  notArrow(method)
        }
        store.commit('loading', true)
        const isData = postType.some( m => m == method.toUpperCase())
        const query = !isData ? new URLSearchParams(parameter).toString() : '';
        const requestUri = new URL(`${baseUri}${path}`)
        const headers = new Headers(header)
        headers.append('Content-Type', 'application/json')
        requestUri.search = query
        let res = null
        try{
            res = await fetch(requestUri, {
                method: method,
                params: (!isData ? parameter : null),
                body: isData ? JSON.stringify(parameter) : null,
                headers,
                mode: 'cors',
            }).catch(e => {throw e})
        }catch(e){
            store.commit('loading', false)
            fetchError(e)
            throw e
        }
        store.commit('loading', false)
        return res
    }
    /**
     * ファイルアップロード
     * @param {String} path 
     * @param {String} method 
     * @param {FormData} file 
     * @param {Object} header 
     * @returns Object 
     */
    async function upload(path, method, file, header = {}){
        const requestUri = new URL(`${baseUri}${path}`)
        const headers = new Headers(header)
        if(!['POST', 'PUT', 'PATCH'].some(m => m == method.toUpperCase())){
            throw  notArrow(method)
        }
        const res = await fetch(requestUri, {
            method: method,
            body: file,
            headers,
            mode: 'cors',
        }).catch(e => {
            fetchError(e)
            throw e
        })
        return res
    }
    function notArrow(method){
        const msg = t('message.NM0001', [method])
        store.commit('error', msg)
        return  msg
    }
    function fetchError(e){
        console.error(JSON.stringify(e))
        store.commit('error', t('message.NM0000', [e.message]))
    }
    return {request, upload}
}
export async function useResponse(res){
    const resType = res.headers.get('content-type')
    const getData = async (resType, res) => {
        if(resType == 'application/json'){
            return await res.json()
        }else if(resType.includes('text/') || !res.ok){
            return await res.text()
        }else{
            return await res.blob()
        }
    }
    const body =  await getData(resType, res)
    const resData = reactive(body)
    const allRes =  reactive(res)
    
    const status = computed({
        get: () => allRes.status
    })
    const ok = computed({
        get: () => allRes.ok
    })
    const statusText = computed({
        get: () => allRes.statusText
    })
    const data = computed({
        get: () => resData
    })
    const response = computed({
        get: () => allRes,
    })
    return {data, status, ok, statusText, response}
}
/**
 * APIのリクエストのベースクラス
 */
export class RestAccessor{
    constructor(resource){
        this._accessor = useRestAccessor()
        this._resource = resource
        this._t = i18n.global.t
    }
    /**
     * Getリクエスト
     * 振る舞いを変える場合は継承先でオーバーライド
     * @param {String} id 
     * @param {Object} parameter
     * @returns response
     */
    get = async ({id = null, parameter = {}}) => {
        const res = await this._accessor.request(this.directory(id, parameter), 'GET', parameter)
        const {data, status, ok, statusText, response} = await useResponse(res)
        this._response = response
        if(!ok.value){
            this.notOk(status.value, statusText.value, data.value )
        }
        return data.value
    }
    /**
     * POST or PUT リクエスト
     * 振る舞いを変える場合は継承先でオーバーライド
     * @param {Object} parameter 
     * @param {String} id 
     * @returns response
     */
    push = async (parameter, id = null) => {
        const res = await this._accessor.request(this.directory(id, parameter), this.method(id, parameter), parameter)
        const {data, status, ok, statusText, response} = await useResponse(res)
        this._response = response
        if(!ok.value){
            this.notOk(status.value, statusText.value, data.value )
        }
        return data.value
    }
    /**
     * DELETEリクエスト
     * @param {String} id
     * @param {Object} parameter 
     * @returns response
     */
    delete = async ({id = null, parameter = {}}) => {
        // idなしはエラー
        if(!this.isIdResource(id)){
            this.noId()
            return null
        }
        const res = await this._accessor.request(this.directory(id, parameter), 'DELETE', parameter)
        const {data, status, ok, statusText, response} = await useResponse(res)
        this._response = response
        if(!ok.value){
            this.notOk(status.value, statusText.value, data.value )
        }
        return data.value
    }
    /**
     * id付きリクエストか否か
     * 振る舞いを変える場合は継承先でオーバーライド
     * @param {String} id 
     * @param {Object} parameter 
     * @returns boolean
     */
    isIdResource = (id, parameter) => id !== null
    /**
     * id付きリクエストの場合にリソースへidを付与する
     * 振る舞いを変える場合は継承先でオーバーライド
     * @param {String} id 
     * @param {Object} parameter 
     * @returns String
     */
    directory = (id, parameter) => this._resource + (this.isIdResource(id, parameter) ? `/${id}` : '')
    /**
     * push関数のリクエストのHTTPメソッドのPOSTかPUTかの判定
     * idが指定されていればPUTリクエスト、
     * 振る舞いを変える場合は継承先でオーバーライド
     * @param {String} id 
     * @param {Object} parameter 
     * @returns String (POST or PUT)
     */
    method = (id, parameter) => this.isIdResource(id, parameter) ? 'PUT':'POST'
    /**
     * idなしでのリクエストの振る舞い
     * （DELETEメソッドはエラーとする想定）
     * 振る舞いを変える場合は継承先でオーバーライド
     */
    noId = () => {
        store.commit('error', this._t('message.NM0002', ['ID']))
    }
    /**
     * 異常レスポンスの振る舞い
     * 振る舞いを変える場合は継承先でオーバーライド
     * @param {Number} status 
     * @param {String} statusText 
     */
    notOk = (status, statusText, data) => {
        store.commit('error',  this._t('message.NM0003', [statusText, status]))
        throw data
    }
    /**
     * デバッグ用メソッド
     * @returns Response
     */
    debugger = () => this._response
}