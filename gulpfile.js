/**
 * Created by Sanggoo.
 * Date: 2017-02-13
 */

var config = {};

var gulp = require('gulp'),
	gulpSequence = require('gulp-sequence'),
	gulpif = require('gulp-if'),
	sourcemaps = require('gulp-sourcemaps'),
	concat = require('gulp-concat'),
	uglify = require('gulp-uglify'),
	rename = require('gulp-rename'),
	del = require('del'),
	merge = require('merge2'),
	plumber = require('gulp-plumber'),
	zip = require('gulp-vinyl-zip'),
	remoteSrc = require('gulp-remote-src'),
	sass = require('gulp-sass'),
	spritesmith = require('gulp.spritesmith'),
	util = require('util'),
	path = require('path')
	;

function src(src) {
	if(typeof src === 'string')
		src = [src];

	if(src.length < 1)
		return null;

	var stream = merge();
	for(var i = 0; i < src.length; i++) {
		if(src[i].indexOf('http') == 0) {
			stream.add(remoteSrc(src[i], {base:''}));
		} else {
			stream.add(gulp.src(src[i]));
		}
	}

	return stream;
}

gulp.task('default', gulpSequence('loadConfig', 'js', 'sprite', 'sass', 'watch'));

gulp.task('loadConfig', function() {
	delete require.cache[path.resolve('./gulpconfig.json')];
	config = require('./gulpconfig');
});

gulp.task('js', function() {
	var js = config['js'];
	return merge(js.src.map(function(obj) {
		var stream = src(obj.item);

		if(stream) {
			return stream
				.pipe(plumber({
					errorHandler: function (err) {
						console.error(err.toString());
						this.emit('end');
					}
				}))
				.pipe(gulpif(obj.concat || obj.uglify, sourcemaps.init({loadMaps:true})))
				.pipe(gulpif(obj.uglify, uglify({mangle:true, preserveComments:'license'})))
				.pipe(gulpif(obj.concat, concat(obj.name+'.js')))
				.pipe(gulpif(obj.uglify, rename({suffix:'.min'})))
				.pipe(gulpif(obj.concat || obj.uglify, sourcemaps.write('.', {sourceRoot:'../../src/js'})))
				.pipe(gulp.dest(obj.dest || js.dest));
		} else
			return null;
	}));
});

gulp.task('sprite', function() {
	var sprite = config['sprite'];
	return merge(sprite.src.map(function(obj) {
		var option = util._extend({}, obj.option);
		option['imgPath'] = option['imgPath'] + '?' + sprite['date'];

		var spriteData = gulp.src(obj.item)
			.pipe(plumber({
				errorHandler: function (err) {
					console.error(err.toString());
					this.emit('end');
				}
			}))
			.pipe(spritesmith(option));

		return merge(spriteData.img.pipe(gulp.dest(obj.dest.img)), spriteData.css.pipe(gulp.dest(obj.dest.css)))
	}));
});

gulp.task('sass', function() {
	var s = config['sass'];
	return merge(s.src.map(function(obj) {
		return gulp.src(obj.item)
			.pipe(plumber({
				errorHandler: function (err) {
					console.error(err.toString());
					this.emit('end');
				}
			}))
			.pipe(sourcemaps.init({loadMaps:true}))
			.pipe(sass({outputStyle:'compressed'}).on('error', sass.logError))
			// .pipe(gulpif(obj.concat, concat(obj.name+'.css')))
			.pipe(gulpif(typeof obj['name'] !== 'undefined', rename(obj.name+'.min.css')))
			.pipe(sourcemaps.write('.', {sourceRoot:'../../src/scss'}))
			.pipe(gulp.dest(obj.dest || s.dest));
	}));
});

gulp.task('watch', function() {
	var js = config['js'];
	gulp.watch(js['watch'], ['js']);

	var sprite = config['sprite'];
	gulp.watch(sprite['watch'], ['sprite']);

	var s = config['sass'];
	gulp.watch(s['watch'], ['sass']);

	gulp.watch('gulpconfig.json', ['loadConfig', 'js', 'sprite']);
});

