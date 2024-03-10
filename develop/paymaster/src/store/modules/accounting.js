export default {
    namespaced: true,
    state: {
      carriedTransaction: []
    },
    getters:{
        carriedTransactioners(state){
            return state.carriedTransaction.map(item => item.transactioner)
        }
    },
    mutations:{
        carriedTransaction(state, transaction){
            state.carriedTransaction = transaction
        }
    },
    actions:{
        isCarried({state}, transactioner){
            console.log(state.carriedTransaction.some(item => item.transactioner == transactioner))
            return state.carriedTransaction.some(item => item.transactioner == transactioner)
        }
    },
  }