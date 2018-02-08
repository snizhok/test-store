var gulp = require('gulp');
var concat = require('gulp-concat');
var order = require('gulp-order');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');

gulp.task('css', function () {
    var src = 'resources/css/**/*';
    var dest = 'public/css';
    var sort_order = [
        'libs/**/*',
        'main.css',
        '**/*'
    ];
    return gulp.src(src)
        .pipe(order(sort_order))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(concat('app.min.css'))
        .pipe(gulp.dest(dest));
});

gulp.task('js', function () {
    var src = 'resources/js/**/*';
    var dest = 'public/js';
    var sort_order = [
        'libs/**/*',
        '**/*'
    ];
    return gulp.src(src)
        .pipe(order(sort_order))
        .pipe(uglify().on('error', function (err) {
            console.log(err.toString());
            this.emit('end');
        }))
        .pipe(concat('app.min.js'))
        .pipe(gulp.dest(dest));
});

gulp.task('default', ['css', 'js'], function () {
    gulp.watch('resources/css/**/*', ['css']);
    gulp.watch('resources/js/**/*', ['js']);
});
