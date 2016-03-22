var gulp = require('gulp'),
	sass = require('gulp-ruby-sass')
	sourcemaps = require('gulp-sourcemaps')
	autoprefixer = require('gulp-autoprefixer')
	cleanCSS = require('gulp-clean-css');

gulp.task('proces-styles', function() {
	return sass('src/css/style.scss', { style: 'compressed' })
		.on('error', sass.logError)
		.pipe(sourcemaps.init())
		.pipe(autoprefixer('last 3 version'))
		.pipe(cleanCSS({compatibility: 'ie8'}))
		.minify()
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('/'));
});

gulp.task('default', function() {
  console.log('test');
});