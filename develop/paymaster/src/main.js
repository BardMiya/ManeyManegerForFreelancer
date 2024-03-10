import { createApp } from 'vue'
import './style.css'
import App from '@/App.vue'

// Vuetify
import { vuetify } from '@/plugin/vuetify'

// Vue-router
import { router } from '@/plugin/router'

// Vuex
import {store} from '@/store/index'

// i18n
import {i18n} from '@/plugin/i18n'

// const vuetify = createVuetify({
//   components,
//   directives,
// })

createApp(App)
    .use(vuetify)
    .use(router)
    .use(store)
    .use(i18n)
    .mount('#app')

// Original source
// createApp(App).mount('#app')
