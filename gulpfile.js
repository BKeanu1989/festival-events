'use strict';

var gulp = require('gulp');

var sass = require('gulp-sass');
var wpPot = require('gulp-wp-pot');
var sassDir = './assets/scss/**/*.scss';

sass.compiler = require('node-sass');

gulp.task('sass', function () {
    return gulp.src(sassDir)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./dist/css'));
});

gulp.task('sass:watch', function () {
    gulp.watch(sassDir, ['sass']);
});

gulp.task('pot', function () {
    return gulp.src('./**/*.php')
        .pipe(wpPot( {
            domain: 'festival-events',
            package: 'StoreFront Child'
        } ))
        .pipe(gulp.dest('./languages/festival-events.pot'));
});