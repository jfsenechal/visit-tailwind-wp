import {defineConfig} from 'vite'
import {fileURLToPath, URL} from "node:url";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [],
    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./src", import.meta.url)),
            images: fileURLToPath(new URL("./src/public/images", import.meta.url))
        }
    },
    build: {
        watch: {
            // https://rollupjs.org/guide/en/#watch-options
        },
        rollupOptions: {
            input: {
                appFiltreAdmin: 'src/ol.js',
            },
            output: {
                assetFileNames: 'js/oljf.css',
                entryFileNames: 'js/oljf.js',
            },
        }
    }
});
