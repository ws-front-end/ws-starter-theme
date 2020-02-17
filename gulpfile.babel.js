/**
 * Gulpfile.
 *
 */
const gulp = require('gulp');
const newer = require('gulp-newer');
const plumber = require('gulp-plumber');
const notifier = require('node-notifier');
const imagemin = require('gulp-imagemin');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const postcssAssets = require('postcss-assets');
const path = require('path');
const webpack = require('webpack-stream');
const UglifyJsPlugin = require('terser-webpack-plugin');
const browsersync = require('browser-sync');
const autoprefixer = require('autoprefixer');
const cssMqpacker = require('css-mqpacker');
const sortCSSmq = require('sort-css-media-queries');
const cssnano = require('cssnano');
const shell = require('gulp-shell');
const replace = require('gulp-replace');
const gulpStylelint = require('gulp-stylelint');

/**
 * Configuration.
 */
const projectUrlName = 'anne';
const projectUrl = `${projectUrlName}.test`;
const projectName = path.basename(__dirname);
const enableNotify = true;
const dir = {
  src: './',
  build: './',
};

const browserSyncOptions = {
  proxy: projectUrl,
  open: true,
  notify: true,
  ghostMode: false,
  ui: {
    port: 8001,
  },
};

const php = {
  src: `${dir.src}**/*.php`,
  build: dir.build,
};

const images = {
  src: `${dir.src}assets/src/img/**/*`,
  build: `${dir.build}assets/dist/img/`,
};

let css = {
  src: `${dir.src}assets/src/scss/main.scss`,
  adminsrc: `${dir.src}assets/src/scss/admin-main.scss`,
  watch: `${dir.src}assets/src/scss/**/*`,
  build: dir.build,
  sassOpts: {
    outputStyle: 'compressed',
    imagePath: images.build,
    precision: 3,
    errLogToConsole: true,
  },
  processors: [
    postcssAssets({
      loadPaths: ['assets/dist/img/', 'assets/dist/img/svg'],
      basePath: dir.build,
      baseUrl: `/wp-content/themes/${projectName}/`,
    }),
    autoprefixer,
    cssMqpacker({
      sort: sortCSSmq.desktopFirst,
    }),
  ],
};

if (process.env.NODE_ENV === 'production') {
  css = {
    ...css,
    ...{
      processors: [...css.processors, cssnano],
    },
  };
}

const js = {
  src: `${dir.src}assets/src/js/app.js`,
  watch: `${dir.src}assets/src/js/**/*`,
  build: `${dir.build}assets/dist/js/`,
  filename: {
    dev: 'bundle.min.js',
    build: 'bundle.min.js',
  },
};

let webPackConfig = {
  entry: js.src,
  watch: false,
  mode: 'development',
  devtool: 'cheap-module-eval-source-map',
  externals: {
    jquery: 'jQuery',
  },
  module: {
    rules: [
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
      {
        test: /\.(png|jpe?g|gif|svg|eot|ttf|woff|woff2)$/,
        loader: 'url-loader',
        options: {
          limit: 8192,
        },
      },
    ],
  },
  resolve: {
    modules: [path.resolve(__dirname), 'node_modules'],
  },
  output: {
    filename: js.filename.dev,
    path: path.resolve(js.build),
  },
};

if (process.env.NODE_ENV === 'production') {
  webPackConfig = {
    ...webPackConfig,
    ...{
      watch: false,
      mode: 'production',
      optimization: {
        minimizer: [
          new UglifyJsPlugin({
            terserOptions: {
              parallel: true,
              output: {
                comments: false,
              },
              toplevel: true,
            },
          }),
        ],
      },
    },
  };
  delete webPackConfig.devtool;
}
const browserSyncServer = browsersync.create();

const onError = () => {
  if (enableNotify) {
    notifier.notify({
      title: 'Gulp Task Error',
      message: 'Check the console.',
    });
  }
};

const imagesTask = () => {
  return gulp
    .src(images.src)
    .pipe(plumber({ errorHandle: onError }))
    .pipe(newer(images.build))
    .pipe(imagemin())
    .pipe(gulp.dest(images.build));
};

const cssTask = () => {
  let $retVal = gulp.src(css.src).pipe(plumber({ errorHandle: onError }));

  if (process.env.NODE_ENV !== 'production') {
    $retVal = $retVal.pipe(sourcemaps.init());
  }
  $retVal = $retVal
    .pipe(
      sass(css.sassOpts).on('error', function(err) {
        console.log(err.toString());
        return onError();
      })
    )
    .pipe(postcss(css.processors))
    .pipe(
      rename({
        basename: 'style',
      })
    );
  if (process.env.NODE_ENV !== 'production') {
    $retVal = $retVal.pipe(sourcemaps.write());
  }

  // eslint-disable-next-line global-require
  const packageData = require('./package.json');
  $retVal = $retVal.pipe(replace('__WS_THEME_VERSION__', packageData.version));

  $retVal = $retVal.pipe(gulp.dest(css.build)).pipe(browserSyncServer.stream());

  return $retVal;
};

const lintCssTask = () => {
  return gulp.src(css.watch).pipe(
    gulpStylelint({
      reporters: [{ formatter: 'string', console: true }],
    })
  );
};

const admincssTask = () => {
  let $retVal = gulp.src(css.adminsrc).pipe(plumber({ errorHandle: onError }));

  if (process.env.NODE_ENV !== 'production') {
    $retVal = $retVal.pipe(sourcemaps.init());
  }
  $retVal = $retVal
    .pipe(
      sass(css.sassOpts).on('error', function(err) {
        console.log(err.toString());
        return onError();
      })
    )
    .pipe(postcss(css.processors))
    .pipe(
      rename({
        basename: 'admin-style',
      })
    );
  if (process.env.NODE_ENV !== 'production') {
    $retVal = $retVal.pipe(sourcemaps.write());
  }

  // eslint-disable-next-line global-require
  const packageData = require('./package.json');
  $retVal = $retVal.pipe(replace('__WS_THEME_VERSION__', packageData.version));

  $retVal = $retVal.pipe(gulp.dest(css.build)).pipe(browserSyncServer.stream());

  return $retVal;
};

const jsTask = () => {
  return gulp
    .src(js.src)
    .pipe(plumber({ errorHandle: onError }))
    .pipe(webpack(webPackConfig))
    .pipe(gulp.dest(js.build))
    .pipe(browserSyncServer.reload({ stream: true }));
};

const bumpVersion = () => {
  return gulp.src(js.src).pipe(shell(['npm version patch']));
};

const development = gulp.series(imagesTask, gulp.parallel(admincssTask, cssTask, jsTask));
const build = gulp.series(imagesTask, bumpVersion, gulp.parallel(admincssTask, cssTask, jsTask));
const serve = done => {
  browserSyncServer.init(browserSyncOptions);
  done();
};
const reload = done => {
  browserSyncServer.reload();
  done();
};
const cssSeries = gulp.series(cssTask, lintCssTask);
const watchEverything = () => {
  gulp.watch(php.src, gulp.series(reload));
  gulp.watch(images.src, gulp.series(imagesTask));
  gulp.watch(css.watch, gulp.parallel(cssSeries, gulp.series(admincssTask, lintCssTask)));
  gulp.watch(js.watch, gulp.series(jsTask));
};
const defaultTask = gulp.parallel(development, gulp.series(serve, watchEverything));

export { imagesTask, cssTask, admincssTask, jsTask, watchEverything, build, lintCssTask };

export default defaultTask;
