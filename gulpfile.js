var gulp = require('gulp');
var sass = require('gulp-sass');
var minify = require('gulp-minify');

gulp.task('sass', function() {
    return gulp.src('source/scss/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('public/css'));
});

gulp.task('min-js', function() {
    gulp.src('public/js/*.js')
      .pipe(minify({
          ext: {
              min: '.min.js'
          },
      }))
      .pipe(gulp.dest('public/js'))
});

gulp.task('watch', function() {
    gulp.watch('public/css/*.scss', gulp.series('sass'));
    //gulp.watch('public/js/*.js', gulp.series('min-js'));
});

gulp.task('default', gulp.series('watch'));