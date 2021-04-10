var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass', function() {
    return gulp.src('source/scss/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('public/css'));
});

gulp.task('default', function() {
    gulp.watch('css/*.scss', gulp.series('sass'));
});