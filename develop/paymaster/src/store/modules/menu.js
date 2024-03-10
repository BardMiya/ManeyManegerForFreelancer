export default {
    namespaced: true,
    state: {
      pages: [
        'Home',
        'Works',
        'Invoices',
        'Estimates',
        'Accounting',
        'Ledgers',
        'Products',
        'SignOut',
      ]
    },
    getters:{
        pages(state){
            return state.pages
        }
    }
  }