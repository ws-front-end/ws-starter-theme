/**
 * Gulpfile.
 *
 */
const gulp = require('gulp');
const newer = require('gulp-newer');
const plumber = require('gulp-plumber');
const imagemin = require('gulp-imagemin');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const path = require('path');
const webpack = require('webpack-stream');
const minifyPlugin = require('babel-minify-webpack-plugin');
const browsersync = require('browser-sync');

/**
 * Configuration.
 */
('use strict');
const projectUrlName = 'Gulpfile-35';
const projectUrl = `http://localhost/${projectUrlName}`;
// const projectUrl = `${projectUrlName}.test`;
const projectName = path.basename(__dirname),
	dir = {
		src: './',
		build: './',
	},
	browserSyncOptions = {
		proxy: projectUrl,
		open: true,
		notify: true,
		ghostMode: false,
		ui: {
			port: 8001,
		},
	},
	php = {
		src: dir.src + '**/*.php',
		build: dir.build,
	},
	images = {
		src: dir.src + 'assets/src/img/**/*',
		build: dir.build + 'assets/dist/img/',
	},
	css = {
		src: dir.src + 'assets/src/sass/main.scss',
		watch: dir.src + 'assets/src/sass/**/*',
		build: dir.build,
		sassOpts: {
			outputStyle: 'compressed',
			imagePath: images.build,
			precision: 3,
			errLogToConsole: true,
		},
		processors: [
			require('postcss-assets')({
				loadPaths: ['assets/dist/img/', 'assets/dist/img/svg'],
				basePath: dir.build,
				baseUrl: '/wp-content/themes/' + projectName + '/',
			}),
			require('autoprefixer'),
			require('css-mqpacker'),
			require('cssnano'),
		],
	},
	js = {
		src: dir.src + 'assets/src/js/app.js',
		watch: dir.src + 'assets/src/js/**/*',
		build: dir.build + 'assets/dist/js/',
		filename: 'bundled-scripts.js',
	},
	browserSyncServer = browsersync.create();

const imagesTask = () => {
	return gulp
		.src(images.src)
		.pipe(plumber())
		.pipe(newer(images.build))
		.pipe(imagemin())
		.pipe(gulp.dest(images.build));
};
const cssTask = () => {
	return gulp
		.src(css.src)
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sass(css.sassOpts))
		.pipe(postcss(css.processors))
		.pipe(
			rename({
				basename: 'style',
			})
		)
		.pipe(sourcemaps.write({ includeContent: false }))
		.pipe(sourcemaps.write(css.build))
		.pipe(gulp.dest(css.build))
		.pipe(browserSyncServer.stream());
};
const jsTask = () => {
	return gulp
		.src(js.src)
		.pipe(plumber())
		.pipe(
			webpack({
				watch: true,
				entry: js.src,
				mode: 'production',
				externals: {
					jquery: 'jQuery',
				},
				module: {
					rules: [
						{
							test: /\.loader\.js$/,
							use: [ 'script-loader' ]
						},
						{
							test: /\.js?$/,
							use: {
								loader: 'babel-loader',
								options: {
									exclude: /node_modules/,
									babelrc: true,
								},
							},
						},
						{
							test: /\.css$/,
							use: ['style-loader', 'css-loader'],
						},
					],
				},
				plugins: [new minifyPlugin()],
				resolve: {
					modules: [path.resolve(__dirname), 'node_modules'],
				},
				output: {
					filename: 'bundle.min.js',
					path: path.resolve(js.build),
				},
				performance: {
					hints: 'warning',
				},
			})
		)
		.pipe(gulp.dest(js.build));
};

const build = gulp.series(imagesTask, gulp.parallel(cssTask, jsTask));

const serve = done => {
	browserSyncServer.init(browserSyncOptions);
	done();
};
const reload = done => {
	browserSyncServer.reload();
	done();
};

const watchEverything = () => {
	gulp.watch(php.src, gulp.series(reload));
	gulp.watch(images.src, gulp.series(imagesTask));
	gulp.watch(css.watch, gulp.series(cssTask));
	gulp.watch(js.watch, gulp.series(jsTask, reload));
};
const defaultTasks = gulp.parallel(build, gulp.series(serve, watchEverything));

export { imagesTask, cssTask, jsTask, watchEverything };

export default defaultTasks;
