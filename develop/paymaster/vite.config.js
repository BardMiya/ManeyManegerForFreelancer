import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue({
    template: {
        transformAssetUrls: {
            base: null,
            includeAbsolute: false,
        },
    },
})],
  resolve: {
    // @をエイリアスとして設定している
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url))
      }
    }
})
