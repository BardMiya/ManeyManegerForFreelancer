import { createStore } from 'vuex'
import menu from './modules/menu'
import accounting from './modules/accounting'

// 新しいストアインスタンスを作成します
export const store = createStore({
  state () {
    return {
      user: 'anyone',
      showingMessage: {
        type: '',
        message: ''
      },
      isLoading: false,
    }
  },
  mutations: {
    info(state, message){
      state.showingMessage.type = 'info'
      state.showingMessage.message = message
    },
    warn(state, message){
      state.showingMessage.type = 'warn'
      state.showingMessage.message = message
    },
    error(state, message){
      state.showingMessage.type = 'error'
      state.showingMessage.message = message
    },
    clearMessage(state){
      state.showingMessage.type = ''
      state.showingMessage.message = ''
    },
    loading(state, isActive){
      state.isLoading = isActive
    }
  },
  getters:{
    user(state){
      return state.user
    },
    apiUri(){
      return import.meta.env.VITE_API_BASE_URL
    },
    showingInfo(state){
      return state.showingMessage.type == 'info' ? state.showingMessage.message : ''
    },
    showingWarn(state){
      return state.showingMessage.type == 'warn' ? state.showingMessage.message : ''
    },
    showingError(state){
      return state.showingMessage.type == 'error' ? state.showingMessage.message : ''
    },
    isLoading(state){
      return state.isLoading
    }
  },
  modules: {
    menu: menu,
    accounting: accounting,
  }
})