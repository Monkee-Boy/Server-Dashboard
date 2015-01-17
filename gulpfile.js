var gulp = require('gulp'),
sass = require('gulp-ruby-sass'),
concat = require('gulp-concat'),
uglify = require('gulp-uglify'),
minifyCSS = require('gulp-minify-css'),
jshint = require('gulp-jshint'),
rename = require('gulp-rename'),
size = require('gulp-size'),
pkg = require('./package.json');

var paths = {
  styles: './public/css/style.scss',
  scripts: './public/js/main.js'
};

gulp.task('styles', function () {
  gulp.src(paths.styles)
  .pipe(sass({ style: 'expanded' })) // Add source maps after figuring out minify issue
  .pipe(gulp.dest('./public/css/'))
  .pipe(rename({ suffix: '.min' }))
  .pipe(minifyCSS())
  .pipe(size())
  .pipe(gulp.dest('./public/css/'));
});

gulp.task('scripts', ['lint', 'plugins'], function() {
  gulp.src(paths.scripts)
  .pipe(concat('app.js'))
  .pipe(gulp.dest('./public/js/'))
  .pipe(rename('app.min.js'))
  .pipe(uglify({ preserveComments: 'some' }))
  .pipe(size())
  .pipe(gulp.dest('./public/js/'));
});

gulp.task('plugins', function() {
  return gulp.src(['./bower_components/jquery/dist/jquery.min.js'])
  .pipe(rename('jquery.min.js'))
  .pipe(gulp.dest('./public/js/'));
});

gulp.task('lint', function () {
  return gulp.src('./public/js/main.js')
  .pipe(jshint('.jshintrc'))
  .pipe(jshint.reporter('jshint-stylish'));
});

// Rerun the task when a file changes
gulp.task('watch', function() {
  gulp.watch('./public/js/*.js', ['scripts']);
  gulp.watch('./public/css/*.scss', ['styles']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['styles', 'scripts']);
