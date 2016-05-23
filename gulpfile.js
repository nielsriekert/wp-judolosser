var gulp = require('gulp'),
	sass = require('gulp-sass')
	sourcemaps = require('gulp-sourcemaps')
	autoprefixer = require('gulp-autoprefixer')
	uglify = require('gulp-uglify'),
	imagemin = require('gulp-imagemin'),
	cache = require('gulp-cache'),
	concat = require('gulp-concat'),
	ftp = require('vinyl-ftp');


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
		.pipe(gulp.dest('images'));
});

gulp.task('deploy', function(){
	var ftppass = require('./.ftppass.json');

    var conn = ftp.create( {
        host: 'judolosser.nl',
        user: ftppass.judolosser.username,
        password: ftppass.judolosser.password,
        parallel: 10
        //log: gutil.log
    } );

    var globs = [
        'images/**',
        'js/**',
        '*.php',
        '*.css'
    ];

    // using base = '.' will transfer everything to /public_html correctly
    // turn off buffering in gulp.src for best performance

    return gulp.src( globs, { base: '.', buffer: false } )
        .pipe( conn.newer( '/domains/judolosser.nl/public_html/dev/wp-content/themes/judolosser' ) ) // only upload newer files
        .pipe( conn.dest( '/domains/judolosser.nl/public_html/dev/wp-content/themes/judolosser' ) );

} );

gulp.task('watch', ['styles', 'scripts', 'images', 'deploy'], function() {
	gulp.watch('src/scss/*.scss', ['styles']);
	gulp.watch('src/js/*.js', ['scripts']);
	gulp.watch('src/images/*.+(png|jpg|gif|svg)', ['images']);
	gulp.watch(['images/**', 'js/**', '*.php', '*.css'], ['deploy']);
});


gulp.task('default', ['watch']);