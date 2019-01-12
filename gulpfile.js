'use strict';

var gulp = require('gulp');

var sass = require('gulp-sass');
var wpPot = require('gulp-wp-pot');

var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var pump = require('pump');


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

gulp.task('concat-admin-js', function() {
    return gulp.src('./assets/admin/js/*.js')
        .pipe(concat('festival-events.js'))
        .pipe(gulp.dest('admin'))
})

gulp.task('concat-frontend-js', function() {
    return gulp.src('./assets/client/js/*.js')
        .pipe(concat('festival-events-frontend.js'))
        .pipe(gulp.dest('client'))
})

gulp.task('concat-classes-js', function() {
    let files = ['Validator.js', 'RadioValidator.js', 'InputValidator.js'];
    files = files.map((x) => {
        return './assets/client/classes/' + x;
    })
    // console.log(files);
    return gulp.src(files)
        .pipe(concat('classes-frontend.js'))
        .pipe(gulp.dest('./assets/client/js'));
})

gulp.task('uglify-admin', function() {
    return gulp.src('./dist/admin/festival-events.js')
        .pipe(uglify())
        .pipe(rename('admin-festival-events.min.js'))
        .pipe(gulp.dest('dist/.'))
});

gulp.task('uglify-frontend', function() {
    return gulp.src('./dist/client/festival-events-frontend.js')
        .pipe(uglify())
        .pipe(rename('festival-events-frontend.min.js'))
        .pipe(gulp.dest('dist/.'))
});

