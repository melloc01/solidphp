var gulp = require('gulp'), 
	minify = require('gulp-minify'), 
	concat = require('gulp-concat'), 
	uglify = require('gulp-uglify');
    minifyCSS = require('gulp-minify-css');
    less = require('gulp-less');
    rimraf = require('gulp-rimraf');
    ignore = require('gulp-ignore');

gulp.task('default', ['less'], function() {

	var rand_name = Math.random();
	rand_name *= 100000;
	rand_name += '';
	rand_name = rand_name.substr(0,4);

	var js_name = 'app_'+rand_name+'.min.js';
	var css_name = 'app_'+rand_name+'.min.css';

	gulp.src(['*/js/*'] )
	    .pipe(uglify())
	    .pipe(concat(js_name))
		.pipe(ignore('node_modules/**'))
		.pipe(ignore('dist/**'))
	    .pipe(gulp.dest('dist/bundle/js'));

	gulp.src(['*/css/*', 'dist/boootstrap.css'] )
	    .pipe(minifyCSS())
		.pipe(ignore('node_modules/**'))
		.pipe(ignore('dist/**'))
	    .pipe(concat(css_name))
	    .pipe(gulp.dest('dist/bundle/css'));

/*
gulp.task('watch', function() {
    gulp.watch('./*.less', ['less']);  // Watch all the .less files, then run the less task
});

gulp.task('default', ['watch']); // Default will run the 'entry' watch task*/
});


gulp.task('less', ['clean-dist'], function() {
 	gulp.src('plugins/bootstrap_less/less/bootstrap.less')  // only compile the entry file
 	.pipe(less())
	.pipe(minifyCSS())
 	.pipe(gulp.dest('dist'))
});

gulp.task('clean-dist', function() {
  	return gulp.src('./dist/**', { read: false }) // much faster
    .pipe(rimraf());
});
