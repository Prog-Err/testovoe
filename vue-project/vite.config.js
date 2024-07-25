import { fileURLToPath, URL } from 'node:url'

import { defineConfig,loadEnv} from 'vite'
import vue from '@vitejs/plugin-vue'


export default defineConfig(({ command, mode }) => {
  const env = loadEnv(mode, process.cwd(), '')
  return {
    // vite config
    plugins: [
      vue(),
    ],
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url))
      }
    },
    server:{
      proxy:{
        '/api':{
          target: env.VITE_APP_URL,
          changeOrigin:true,
          // rewrite:(path)=>path.replace(/^\/api/,"")
        }
      },
    },
    build: {
      outDir: '../public',
      emptyOutDir: true, 
    }
    
  }
})
