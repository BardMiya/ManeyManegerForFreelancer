import { useRestAccessor, useResponse} from '@/composable/restAccessor'
import {store} from '@/store/index'
import { i18n } from '@/plugin/i18n'
import { describe, expect, it, vi, beforeEach, afterEach } from 'vitest';

const t = i18n.global.t

/**
 * rest
 */
describe('Test of requesting method of restAccessor', ()=>{
    const rest = useRestAccessor()
    let resStatus = 200
    let success = true
    const mockRes = {status: resStatus, ok: success}
    const mockFetchThrow = 'error mock fetch'
    /**
     * 各テストの実行前処理
     */
    beforeEach(async () => {
        // fetchのMock化
        const mockFetch = vi
            .spyOn(global, 'fetch')
            .mockImplementation(async (uri, options) => {
                if(uri.pathname.includes('throw')){
                    throw { message: mockFetchThrow}
                }else{
                    return new Response('{ "body": "mock" }', mockRes)
                }
            })
    })
    /**
     * 各テストの実行後処理
     */
    afterEach(() => {
        // Mockのリセット
        vi.restoreAllMocks()
    })
    /**
     * TEST: GETリクエスト
     */
    it('Test: GET request', async () =>{
        const rs = await rest.request('/test', 'GET')
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: GETリクエスト（パラメータあり）
     */
    it('Test: GET request', async () =>{
        const rs = await rest.request('/test', 'GET', { para: 'xxxx'})
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: POSTリクエスト（パラメータあり）
     */
    it('Test: POST request', async () =>{
        const rs = await rest.request('/test', 'POST', { para: 'xxxx'})
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: PUTリクエスト（パラメータあり）
     */
    it('Test: PUT request', async () =>{
        const rs = await rest.request('/test', 'PUT', { para: 'xxxx'})
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: DELETEリクエスト（パラメータあり）
     */
    it('Test: DELETE request', async () =>{
        const rs = await rest.request('/test', 'DELETE', { para: 'xxxx'})
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: PATCHリクエスト（パラメータあり）
     */
    it('Test: PATCH request', async () =>{
        const rs = await rest.request('/test', 'PATCH', { para: 'xxxx'})
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: 関係なしメソッドリクエスト（パラメータあり）
     * リクエストせずに、例外をthrow
     */
    it('Test: XXXX request', async () =>{
        return await rest.request('/test', 'XXXX', { para: 'xxxx'}).catch(e => {
            expect(e).toBe(t('message.NM0001', ['XXXX']))
            expect(store.getters['showingError']).toBe(t('message.NM0001', ['XXXX']))
        })
    })
    /**
     * TEST: リクエストで例外
     * 例外を期待
     */
    it('Test: API is responsed error', async () =>{
        const rs = await rest.request('/throw', 'GET', { para: 'xxxx'}).catch(e => {
            expect(e.message).toBe(mockFetchThrow)
            expect(store.getters['showingError']).toBe(t('message.NM0000', [mockFetchThrow]))
        })
    })
    /**
     * TEST: uploadメソッド
     * POSTメソッド
     */
    it('Test: Upload by POST', async () =>{
        const file = new FormData()
        const rs = await rest.upload('/test', 'POST', file)
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: uploadメソッド
     * PUTメソッド
     */
    it('Test: Upload by PUT ', async () =>{
        const file = new FormData()
        const rs = await rest.upload('/test', 'PUT', file)
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: uploadメソッド
     * PATCHメソッド
     */
    it('Test: Upload by PATCH ', async () =>{
        const file = new FormData()
        const rs = await rest.upload('/test', 'PATCH', file)
        const body = await rs.json()
        expect(rs.status).toEqual(200)
    })
    /**
     * TEST: uploadメソッド
     * 不可メソッド
     */
    it('Test: Upload by unarrouwed method ', async () =>{
        const file = new FormData()
        const res = await rest.upload('/test', 'GET', file).catch(e => {
            expect(e).toBe(t('message.NM0001', ['GET']))
            expect(store.getters['showingError']).toBe(t('message.NM0001', ['GET']))
        })
    })
    /**
     * TEST: リクエストで例外
     * 例外を期待
     */
    it('Test: API is responsed error', async () =>{
        const file = new FormData()
        const res = await rest.upload('/throw', 'POST', file).catch(e => {
            expect(e.message).toBe(mockFetchThrow)
            expect(store.getters['showingError']).toBe(t('message.NM0000', [mockFetchThrow]))
        })
    })
})
describe('Test of useResponse', () => {
    const headerJsonType = new Headers()
    const headerTextType = new Headers()
    const headerBlobType = new Headers()
    headerJsonType.append('content-type', 'application/json')
    headerTextType.append('content-type', 'text/plain')
    headerBlobType.append('content-type', 'image/plain')
    const mockJson = () => new Promise((resolve, reject) => {
        resolve({test: 'mock res'})
    })
    const mockText = () => new Promise((resolve, reject) => {
        resolve('This is mock res')
    }) 
    const mockBlob = () => new Promise((resolve, reject) => {
        resolve( new Blob([0x1], { type: "image/jpeg" }))
    }) 
    const test = {
        ok: true,
        status: 200,
        headers: headerJsonType,
        json:  mockJson,
        text: mockText,
        blob: mockBlob,
    }
    afterEach(() => {
        store.commit('error', '')
    })
    /**
     * TEST: useRespnse
     * JSONレスポンス想定
     */
    it('Test: response is json', async () => {
        const {data, status, ok, response} = await useResponse(test)
        expect(ok.value).toBe(true)
        expect(status.value).toBe(200)
        expect(data.value.test).toBe('mock res')
        expect(response.value).toEqual(test)
    })
    /**
     * TEST: useRespnse
     * TEXTレスポンス想定
     */
    it('Test: response is text', async () => {
        test.headers = headerTextType
        test.status = 201
        const {data, status, ok, response} = await useResponse(test)
        expect(ok.value).toBe(true)
        expect(status.value).toBe(201)
        expect(data.value).toBe('This is mock res')
        expect(response.value).toEqual(test)
    })
    /**
     * TEST: useRespnse
     * BLOBレスポンス想定
     */
    it('Test: response is else', async () => {
        test.headers = headerBlobType
        test.status = 202
        const {data, status, ok, response} = await useResponse(test)
        expect(ok.value).toBe(true)
        expect(status.value).toBe(202)
        expect(data.value).toEqual(new Blob([0x1], {type: "image/jpeg"}))
        expect(response.value).toEqual(test)
    })
})
