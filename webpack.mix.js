const { join, resolve } = require('path')
const { copySync, removeSync } = require('fs-extra')
const mix = require('laravel-mix')
const autoprefixer = require('autoprefixer')
require('laravel-mix-versionhash')
// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')
mix
    .js('resources/js/app.js', 'public/dist/js').vue({
        extractStyles: true

    })
    .sass('resources/sass/app.sass', 'public/dist/css')
    .options({
        processCssUrls: false,
        postCss: [ autoprefixer() ]
    })

    .disableNotifications()
    .webpackConfig({ devtool: 'source-map' })
if (mix.inProduction()) {
    mix
        // .extract() // Disabled until resolved: https://github.com/JeffreyWay/laravel-mix/issues/1889
        // .version() // Use `laravel-mix-versionhash` for the generating correct Laravel Mix manifest file.
        .versionHash()
} else {
    mix.sourceMaps()
}

mix.webpackConfig({
    plugins: [

        // new BundleAnalyzerPlugin()
    ],
    resolve: {
        extensions: [ '.js', '.json', '.vue' ],
        alias: {
            '~': join(__dirname, './resources/js')
        }
    },
    output: {
        chunkFilename: 'dist/js/[chunkhash].js',
        path: resolve(__dirname, mix.inProduction() ? './public/build' : './public')
    },
    stats: {
        children: true
    }

})

mix.then(() => {
    if (mix.inProduction()) {
        process.nextTick(() => publishAseets())
    }
})

function publishAseets() {
    const publicDir = resolve(__dirname, './public')

    removeSync(join(publicDir, 'dist'))
    copySync(join(publicDir, 'build', 'dist'), join(publicDir, 'dist'))
    removeSync(join(publicDir, 'build'))
    copySync(join(publicDir, 'assets', 'files'), join(publicDir, 'dist/css/files'))
    copySync(join(publicDir, 'assets', 'fonts'), join(publicDir, 'dist/fonts'))
}
