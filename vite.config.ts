import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
        vueDevTools(),
    ],
    build: {
        minify: true,
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks: (path) => {
                    if (path.includes("axios")) {
                        return "axios";
                    }

                    if (path.includes("tiptap")) {
                        return "tiptap";
                    }

                    if (path.includes("node_modules")) {
                        return "vendor";
                    }
                }
            }
        }
    }
});
