// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi-svg'

export const vuetify = createVuetify(
    // https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
    {
      //テーマの設定
      theme: {
        defaultTheme: "light",
         themes: {
           light: {
             colors: {
               primary: '#47845E', // #1E88E5
               secondary: '#d3dbd6',
               error: '#FF4800',
               success: '#1EA1FF',
               warning: '#FFA103',        
             },
           },
         },
      },
      // icon設定
      icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
      },
      // 全てのコンポーネントを利用
      components,
      // 全てのディレクティブを利用
      directives
    }
  )