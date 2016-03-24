var gulp = require('gulp'),
	sass = require('gulp-sass')
	sourcemaps = require('gulp-sourcemaps')
	autoprefixer = require('gulp-autoprefixer')
	uglify = require('gulp-uglify'),
	imagemin = require('gulp-imagemin'),
	cache = require('gulp-cache'),
	concat = require('gulp-concat');;


gulp.task('styles', function() {
	return gulp.src('src/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
		.pipe(autoprefixer('last 3 version'))
		.pipe(sourcemaps.write('src/maps', {
			includeContent: false,
			sourceRoot: 'source'
		}))
		.pipe(gulp.dest('.'));
});

gulp.task('scripts', function() {
	return gulp.src(['./src/js/utils.js', './src/js/carousel.js'])
		.pipe(uglify())
		.pipe(concat('main.min.js'))
		.pipe(gulp.dest('./js'));
});

gulp.task('images', function(){
	return gulp.src('src/images/*.+(png|jpg|gif|svg)')
		.pipe(cache(imagemin({
			progressive: true
		})))
		.pipe(gulp.dest('images'))
});

gulp.task('watch', ['styles', 'scripts', 'images'], function() {
	gulp.watch('src/scss/*.scss', ['styles']);
	gulp.watch('src/js/*.js', ['scripts']);
	gulp.watch('src/images/*.+(png|jpg|gif|svg)', ['images']);
});


gulp.task('default', ['watch']);