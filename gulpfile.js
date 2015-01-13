var gulp			= require('gulp'),
	stylus			= require('gulp-stylus'),
	watch			= require('gulp-watch'),
	prefix			= require('gulp-autoprefixer'),
	coffee			= require('gulp-coffee'),
	concat			= require('gulp-concat'),
	livereload		= require('gulp-livereload'),
	rename			= require('gulp-rename'),
	cssmin			= require('gulp-cssmin'),
	sourcemaps		= require('gulp-sourcemaps'),
	plumber			= require('gulp-plumber'),
	concat			= require('gulp-concat'),
	processhtml		= require('gulp-processhtml'),
	uglify			= require('gulp-uglify'),
	html2js			= require('gulp-html2js'),
	notify			= require('gulp-notify'),
	ngAnnotate		= require('gulp-ng-annotate'),
	wait			= require('gulp-wait')

var paths = {
	bower: './bower_components',
	dev: './public/templates_dev',
	dist: './public/templates'
}

var jsThirdParty = [
	//vendor js here
]

gulp.task('default', function() {
	livereload.listen();

	gulp.watch(paths.dev + '/css/**/*.css').on('change',livereload.changed);
	gulp.watch(paths.dev + '/stylus/**/*.styl', ['build:css']);
	gulp.watch(paths.dev + '/coffee/**/*.coffee', ['build:coffee']);
	gulp.watch(paths.dev + '/partials/**/*.html', ['build:templates']);

});

gulp.task('build', [
	'copy:etc',
	'build:coffee',
	'copy:js:app',
	'copy:js:thirdparty',
	'copy:css',
	'processhtml'
])

gulp.task('build:templates', function() {
	gulp.src(paths.dev + '/partials/**/*.html')
		.pipe(html2js({
			outputModuleName: 'app.templates',
			base: paths.dev

		}))
		.pipe(concat('app.templates.js'))
		.pipe(gulp.dest(paths.dev + '/js'))
		.pipe(notify('Templates compiled'))
})

gulp.task('build:coffee', function() {
	console.log('building');
	gulp.src([
		paths.dev + '/coffee/app.coffee',
		paths.dev + '/coffee/controllers/**/*.coffee',
		paths.dev + '/coffee/directives/**/*.coffee',
		paths.dev + '/coffee/filters/**/*.coffee',
		paths.dev + '/coffee/services/**/*.coffee'
	])
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(concat('app.js'))
		.pipe(coffee())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(paths.dev + '/js'))
		.pipe(notify('Coffee compiled'))
})
gulp.task('build:css', function() {
	gulp.src(paths.dev + '/stylus/core.styl')
		.pipe(plumber())
		.pipe(stylus())
		.pipe(prefix())
		.pipe(rename('styles.css'))
		.pipe(gulp.dest(paths.dev + '/css'))
		.pipe(notify('Stylus compiled'))
})

gulp.task('copy:etc', function() {
	gulp.src([
		paths.dev + '/**/*.php',
		paths.dev + '/fonts/**/*',
		paths.dev + '/lib/**/*',
		paths.dev + '/img/**/*',
		paths.dev + '/bases.pdf',
		paths.dev + '/.htaccess'
	],{
		base: paths.dev
	}).pipe(gulp.dest(paths.dist));
})

gulp.task('copy:css',['build:css'], function() {
	gulp.src([
		paths.bower + '/normalize-css/normalize.css',
		paths.dev + '/css/styles.css'
	])
		.pipe(concat('styles.css'))
		.pipe(cssmin())
		.pipe(gulp.dest(paths.dist+'/css'))
});
gulp.task('copy:js:app', function() {
	gulp.src(paths.dev + '/js/*.js')
		.pipe(concat('app.min.js'))
		.pipe(ngAnnotate())
		.pipe(uglify())
		.pipe(gulp.dest(paths.dist + '/js'))
});
gulp.task('copy:js:thirdparty', function() {
	gulp.src(jsThirdParty)
		.pipe(concat('modules.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(paths.dist + '/js'))
})
gulp.task('processhtml', function() {
	gulp.src(paths.dev + '/index.php')
		.pipe(wait(5000))
		.pipe(processhtml('index.php'))
		.pipe(gulp.dest(paths.dist))
})