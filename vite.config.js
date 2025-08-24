import { resolve } from 'path'

export default {
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        origin: 'http://localhost:5173',
        cors: {
            // Permite el origen de tu WP
            origin: ['http://embudos-prueba.local'],
            methods: ['GET', 'HEAD', 'OPTIONS'],
            allowedHeaders: ['Content-Type', 'Accept']
        },
        // headers explícitos por si tu navegador no muestra cors:true
        headers: {
            'Access-Control-Allow-Origin': 'http://embudos-prueba.local',
            'Access-Control-Allow-Methods': 'GET,HEAD,OPTIONS',
            'Access-Control-Allow-Headers': 'Content-Type,Accept'
        },
        hmr: { host: 'localhost', protocol: 'ws', port: 5173 }
    },
    build: {
        outDir: 'assets/dist',
        assetsDir: '',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                app: resolve(__dirname, 'assets/src/js/app.js'),
            },
            output: {
                entryFileNames: `[name].[hash].js`,
                assetFileNames: `[name].[hash][extname]`,
            },
        },
        cssCodeSplit: false, // un solo CSS generado
    },
    base: '', // rutas relativas
}
