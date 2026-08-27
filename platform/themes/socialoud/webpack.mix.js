const mix = require('laravel-mix');
const path = require('path');

const directory = path.basename(path.resolve(__dirname));
const source = `platform/themes/${directory}`;
const dist = `public/themes/${directory}`;

mix.webpackConfig({ externals: [] });
mix.js(`${source}/assets/js/socialoud.js`, `${dist}/js/socialoud.js`).vue();
mix.copy(`${dist}/js/socialoud.js`, `${source}/public/js/socialoud.js`);
