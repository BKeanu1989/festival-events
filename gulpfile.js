'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');

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