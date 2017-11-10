var gulp = require('gulp');
var chug = require('gulp-chug');
var argv = require('yargs').argv;

config_admin = [
    '--rootPath',
    argv.rootPath || '../../../../../../../web/assets/',
    '--nodeModulesPath',
    '../../../../../../../node_modules/'
];

gulp.task('sylius-admin', function() {
    gulp.src('vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/Gulpfile.js', { read: false })
        .pipe(chug({ args: config_admin }));
});

config_shop = [
    '--rootPath',
    argv.rootPath || '../../../../../../../web/assets/',
    '--nodeModulesPath',
    '../../../../../../../node_modules/'
];

gulp.task('sylius-shop', function() {
    gulp.src('vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Gulpfile.js', { read: false })
        .pipe(chug({ args: config_shop }));
});

gulp.task('default', ['sylius-admin', 'sylius-shop']);
