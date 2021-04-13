const gulp = require('gulp');
const sass = require('gulp-sass');
const minify = require('gulp-minify');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');

gulp.task('sass', function() {
    return gulp.src('source/scss/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('public/css'));
});

gulp.task('min-css', () => {
    return gulp.src('public/css/*.css')
        .pipe(sourcemaps.init())
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/css'));
});

gulp.task('min-js', function() {
    gulp.src('public/js/*.js')
      .pipe(minify({
          ext: {
              min: '.min.js'
          },
          ignoreFiles: ['-min.js']
      }))
      .pipe(gulp.dest('public/js'));
});

gulp.task('watch', function() {
    gulp.watch('source/scss/*.scss', gulp.series('sass'));
    gulp.watch('public/js/*.js', gulp.series('min-js'));
});

gulp.task('default', gulp.series('watch'));