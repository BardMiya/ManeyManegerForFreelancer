// import { mount } from '@vue/test-utils'
import { RestAccessor} from '@/composable/restAccessor'
import {store} from '@/store/index'
import { i18n } from '@/plugin/i18n'
import { describe, expect, it, vi, vitest, beforeEach, afterEach } from 'vitest';

const t = i18n.global.t
describe('Test of RestAccessor', () => {
    const headerJsonType = new Headers()
    const headerTextType = new Headers()
    const headerBlobType = new Headers()
    headerJsonType.append('content-type', 'application/json')
    headerTextType.append('content-type', 'text/plain')
    headerBlobType.append('content-type', 'image/plain')
    const mockRes = {resJson: {}, resText: '', resBlob: null }
    const mockJson = () => new Promise((resolve, reject) => {
        resolve(mockRes.resJson)
    })
    const mockText = () => new Promise((resolve, reject) => {
        resolve(mockRes.resText)
    }) 
    const mockBlob = () => new Promise((resolve, reject) => {
        resolve(mockRes.resBlob)
    }) 
    const testResponse = {
        ok: true,
        status: 200,
        statusText: 'OK',
        headers: headerJsonType,
        json:  mockJson,
        text: mockText,
        blob: mockBlob,
    }
    /**
     * 各テストの実行前処理
     */
    beforeEach(async () => {
        const mockFetch = vi
            .spyOn(global, 'fetch')
            .mockImplementation(async (uri, options) => {
                if(uri.pathname.includes('throw')){
                    throw { message: mockFetchThrow}
                }else{
                    return testResponse
                }
            })
    })
    /**
     * 各テストの実行後処理
     */
    afterEach(() => {
        // Mockのリセット
        vi.restoreAllMocks()
        delete global.d
        testResponse.ok=true
        testResponse.status=200
        testResponse.statusText='OK'
        testResponse.headers=headerJsonType
        mockRes.resJson = {}
        mockRes.resText = ''
        mockRes.resBlob = null
    })
    /**
     * TEST: Constractr
     */
    it('Test: constractor', () => {
        const target = new RestAccessor('/test')
        expect(target.constructor.name).toBe('RestAccessor')
    })
    /**
     * TEST: Successful GET
     */
    it('Test: Successful GET', async () => {
        mockRes.resJson = {test: 0}
        
        const target = new RestAccessor('/test')
        const res = await target.get({})
        expect(res.test).toBe(0)
    })
    /**
     * TEST: Successful GET method with id
     */
    it('Test: Successful GET method with a id', async () => {
        mockRes.resJson = {id: '0001'}
        
        const target = new RestAccessor('/test')
        const res = await target.get({id:  mockRes.resJson.id})
        expect(res.id).toBe( mockRes.resJson.id)
    })
    /**
     * TEST: Successful GET method with parameter
     */
    it('Test: GET method request with parameters', async () => {
        mockRes.resJson = {test: false}
        
        const target = new RestAccessor('/test')
        const res = await target.get({parameter: {test_param: 'xxxxx'}})
        expect(res.test).toBe(false)
    })
    /**
     * TEST: Debugger
     */
    it('Test: Debugger', async () => {
        mockRes.resJson = {test: false}
        
        const target = new RestAccessor('/test')
        const res = await target.get({parameter: {test_param: 'xxxxx'}})
        expect(typeof target.debugger()).toEqual('object')
    })
    /**
     * TEST: GET request
     * response is not ok
     */
    it('Test: response is error', async () => {
        testResponse.headers = headerTextType
        testResponse.status = 400
        testResponse.ok = false
        testResponse.statusText = 'Bad request'
        mockRes.resText = 'Mock bad request'
        const target = new RestAccessor('/test')
        const res = await target.get({}).catch(e => {
            expect(e).toBe('Mock bad request')
            expect(store.getters['showingError']).toBe(t('message.NM0003', [testResponse.statusText, testResponse.status]))
        })
    })
    /**
     * TEST: Successful POST
     */
    it('Test: Successful POST', async () => {
        mockRes.resJson = {test: 1}
        
        const target = new RestAccessor('/test')
        const res = await target.push()
        expect(res.test).toBe(1)
    })
    /**
     * TEST: Successful PUT method
     */
    it('Test: Successful PUT method with a id', async () => {
        mockRes.resJson = {id: '0001'}
        
        const target = new RestAccessor('/test')
        const res = await target.push(null, mockRes.resJson.id)
        expect(res.id).toBe( mockRes.resJson.id)
    })
    /**
     * TEST: Successful POST method with parameter
     */
    it('Test: POST method request with parameters', async () => {
        mockRes.resJson = {test: false}
        
        const target = new RestAccessor('/test')
        const res = await target.push({test_param: 'xxxxx'})
        expect(res.test).toBe(false)
    })
    /**
     * TEST: Successful PUT method with parameter
     */
    it('Test: POST method request with parameters', async () => {
        mockRes.resJson = {id: '0002'}
        
        const target = new RestAccessor('/test')
        const res = await target.push({test_param: 'xxxxx'}, mockRes.resJson.id)
        expect(res.id).toBe(mockRes.resJson.id)
    })
    /**
     * TEST: POST request
     * response is not ok
     */
    it('Test: response is error', async () => {
        testResponse.headers = headerTextType
        testResponse.status = 404
        testResponse.ok = false
        testResponse.statusText = 'Not found'
        mockRes.resText = 'Mock Not found'
        const target = new RestAccessor('/test')
        const res = await target.push({}).catch(e => {
            expect(e).toBe('Mock Not found')
            expect(store.getters['showingError']).toBe(t('message.NM0003', [testResponse.statusText, testResponse.status]))
        })
    })
    /**
     * TEST: Successful DELETE method with id
     */
    it('Test: Successful DELETE method with a id', async () => {
        mockRes.resJson = {id: '0003'}
        
        const target = new RestAccessor('/test')
        const res = await target.delete({id:  mockRes.resJson.id})
        expect(res.id).toBe( mockRes.resJson.id)
    })
    /**
     * TEST: Successful DELETE method with parameter
     */
    it('Test: DELETE method request with parameters', async () => {
        mockRes.resJson = {test: false}
        
        const target = new RestAccessor('/test')
        const res = await target.delete({id:'0003', parameter: {test_param: 'xxxxx'}})
        expect(res.test).toBe(false)
    })
    /**
     * TEST: DELETE method Error patern with parameter
     */
    it('Test:  DELETE method Error patern with parameter', async () => {
        mockRes.resJson = {test: false}
        
        const target = new RestAccessor('/test')
        const res = await target.delete({parameter: {test_param: 'xxxxx'}})
        expect(res).toBeNull()
        expect(store.getters['showingError']).toBe(t('message.NM0002', ['ID']))
    })
    /**
     * TEST: GET request
     * response is not ok
     */
    it('Test: response is error', async () => {
        testResponse.headers = headerTextType
        testResponse.status = 500
        testResponse.ok = false
        testResponse.statusText = 'Internal server error'
        mockRes.resText = 'Mock Internal server error'
        const target = new RestAccessor('/test')
        const res = await target.delete({id: 1}).catch(e => {
            expect(e).toBe('Mock Internal server error')
            expect(store.getters['showingError']).toBe(t('message.NM0003', [testResponse.statusText, testResponse.status]))
        })
    })
})
