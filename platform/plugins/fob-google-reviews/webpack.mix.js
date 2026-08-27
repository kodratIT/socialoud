const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/plugins/${directory}`
const dist = `public/vendor/core/plugins/${directory}`

mix
    .sass(`${source}/resources/sass/google-reviews.scss`, `${dist}/css`)
    .js(`${source}/resources/js/google-reviews.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/css/google-reviews.css`, `${source}/public/css`)
        .copy(`${dist}/js/google-reviews.js`, `${source}/public/js`)
}
