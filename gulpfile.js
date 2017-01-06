'use strict';

var gulp 		  = require('gulp');
var sass 		  = require('gulp-sass');
var sourcemaps 	  = require('gulp-sourcemaps');
var autoprefixer  = require('gulp-autoprefixer');
var concat 		  = require('gulp-concat');
var babel         = require('gulp-babel');
var minify   	  = require('gulp-minify');
var sequence      = require('run-sequence');
var jsPaths  	  = [
	'./assets/javascripts/libs/*.js',
	'./assets/javascripts/core/base/*.js',
    './assets/javascripts/core/metafields/*.js',
    './assets/javascripts/core/widgets/*.js',
	'./assets/javascripts/core/main.js'
];


// Compile all sass files in themedir/styles.css
gulp.task('sass', function() {
	return gulp.src('./assets/stylesheets/style.scss')
		.pipe(sourcemaps.init())

		.pipe(sass(
			{outputStyle: 'compressed'}
		).on('error', sass.logError))

		.pipe(autoprefixer({
			browsers: ['last 5 versions'],
			cascade: false
		}))

		.pipe(sourcemaps.write('./'))

		.pipe(gulp.dest('./assets/stylesheets/'));
});	
 

// Watch js/sass files and re-compile on save
gulp.task('app:watch', function(done) {
	sequence(['sass:watch', 'js:watch'], done);
});


// Watch sass files and re-compile on save
gulp.task('sass:watch', function() {
	gulp.watch('./assets/stylesheets/**/*.scss', ['sass']);
});


// Watch js files and re-concatenate on save
gulp.task('js:watch', function() {
	gulp.watch(jsPaths, ['js:concat']);
});


// Concatenate all js files in main.js
gulp.task('js:concat', function() {
	return gulp.src(jsPaths)
		.pipe(sourcemaps.init())
		.pipe(babel())
		.pipe(concat('main.js', {
			newLine:'\n;'
		}))
		.pipe(gulp.dest('assets/javascripts/'));
});


// Minify main.js
gulp.task('js:minify', function() {
  	gulp.src('assets/javascripts/main.js')
    	.pipe(minify({
        	ext:{
            	src:'.js',
            	min:'.min.js'
        	}
    	}))
    	.pipe(gulp.dest('assets/javascripts/'));
});