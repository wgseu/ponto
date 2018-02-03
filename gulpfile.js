var gulp = require('gulp');

var concat = require('gulp-concat');

var cleanCSS = require('gulp-clean-css');

var uglify = require('gulp-uglify');

var util = require('gulp-util');

var browserSync = require('browser-sync').create();

var proxy_host = (process.platform == 'win32') ?'192.168.99.100': 'localhost';

var stylesheets = [
    'resources/assets/css/bootstrap.css',
    'resources/assets/css/agency.css',
    'resources/assets/css/index.css',
    'resources/assets/css/font-awesome.css',
    'resources/assets/css/jquery.datetimepicker.css',
    'resources/assets/css/jquery.thunder.css'
];

var stylesheets_manager = [
    'resources/assets/css/bootstrap.css',
    'resources/assets/css/font-awesome.css',
    'node_modules/animate.css/animate.css',
    'resources/assets/css/index.css',
    'resources/assets/css/custom.css',
    'resources/assets/css/switchery/switchery.css',
    'resources/assets/css/icheck/flat/green.css',
    'resources/assets/css/floatexamples.css',
    'resources/assets/css/simplebar.css',
    'resources/assets/css/daterangepicker.css',
    'resources/assets/css/jquery.datetimepicker.css',
    'resources/assets/css/Treant.css',
    'resources/assets/css/jquery.thunder.css'
];

var javascripts = [
    'resources/assets/js/jquery.thunder.js',
    'resources/assets/js/diacritics.js',
    'resources/assets/js/auto.numeric.min.js',
    'resources/assets/js/jquery.datetimepicker.full.min.js',
    'resources/assets/js/bootstrap.min.js',
    'resources/assets/js/jquery.easing.min.js',
    'resources/assets/js/classie.js',
    'resources/assets/js/cbpAnimatedHeader.js',
    'resources/assets/js/jquery.maskedinput.min.js',
    'resources/assets/js/agency.js',
    'resources/assets/js/index.js'
];

var javascripts_manager = [
    'resources/assets/js/jquery.thunder.js',
    'resources/assets/js/gauge/gauge.min.js',
    'resources/assets/js/moment/moment.min.js',
    'resources/assets/js/chartjs/chart.min.js',
    'resources/assets/js/progressbar/bootstrap-progressbar.min.js',
    'resources/assets/js/icheck/icheck.min.js',
    'resources/assets/js/datepicker/daterangepicker.js',
    'resources/assets/js/flot/jquery.flot.js',
    'resources/assets/js/flot/jquery.flot.pie.js',
    'resources/assets/js/flot/jquery.flot.orderBars.js',
    'resources/assets/js/flot/jquery.flot.time.min.js',
    'resources/assets/js/flot/date.js',
    'resources/assets/js/flot/jquery.flot.spline.js',
    'resources/assets/js/flot/jquery.flot.stack.js',
    'resources/assets/js/flot/curvedLines.js',
    'resources/assets/js/flot/jquery.flot.resize.js',
    'resources/assets/js/diacritics.js',
    'resources/assets/js/switchery/switchery.js',
    'resources/assets/js/nicescroll/jquery.nicescroll.min.js',
    'resources/assets/js/custom.js',
    'resources/assets/js/bootstrap.min.js',
    'resources/assets/js/auto.numeric.min.js',
    'resources/assets/js/inputmask.min.js',
    'resources/assets/js/jquery.datetimepicker.full.min.js',
    'resources/assets/js/jquery-ui/jquery-ui.js',
    'resources/assets/js/jquery.maskedinput.min.js',
    'resources/assets/js/jquery.autocomplete.min.js',
    'resources/assets/js/simplebar.min.js',
    'resources/assets/js/jquery.ddslick.js',
    'resources/assets/js/raphael.js',
    'resources/assets/js/Treant.js',
    'resources/assets/js/index.js'
];

gulp.task('css', function () {
    return gulp.src(stylesheets)
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(concat('index.min.css'))
        .pipe(gulp.dest('public/static/css/'))
        .pipe(browserSync.stream());
});

gulp.task('css-manager', function () {
    return gulp.src(stylesheets_manager)
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(concat('manager.min.css'))
        .pipe(gulp.dest('public/static/css/'))
        .pipe(browserSync.stream());
});

gulp.task('js', function () {
    return gulp.src(javascripts)
        .pipe(uglify())
        .on('error', function (err) { util.log(util.colors.red('[Error]'), err.toString()); })
        .pipe(concat('index.min.js'))
        .pipe(gulp.dest('public/static/js/'));
});

gulp.task('js-manager', function () {
    return gulp.src(javascripts_manager)
        .pipe(uglify())
        .on('error', function (err) { util.log(util.colors.red('[Error]'), err.toString()); })
        .pipe(concat('manager.min.js'))
        .pipe(gulp.dest('public/static/js/'));
});

var syncReload = function (done) {
    browserSync.reload();
    done();
};

gulp.task('js-watch', ['js'], syncReload);

gulp.task('js-manager-watch', ['js-manager'], syncReload);

gulp.task('watch', ['browser-sync'], function () {
    gulp.watch(stylesheets, ['css']);
    gulp.watch(stylesheets_manager, ['css-manager']);
    gulp.watch(javascripts, ['js-watch']);
    gulp.watch(javascripts_manager, ['js-manager-watch']);
    gulp.watch('public/include/template/*.html').on('change', browserSync.reload);
});

gulp.task('browser-sync', function () {
    browserSync.init({
        ui: false,
        proxy: proxy_host + ':8001',
        port: 3001
    });
});

gulp.task('default', ['watch']);
gulp.task('all', ['js', 'js-manager', 'css', 'css-manager']);