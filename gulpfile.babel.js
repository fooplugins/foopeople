/**
 * Gulpfile.
 *
 * Gulp with WordPress.
 *
 * Implements:
 *      1. Live reloads browser with BrowserSync.
 *      2. CSS: Sass to CSS conversion, error catching, Autoprefixing, Sourcemaps,
 *         CSS minification, and Merge Media Queries.
 *      3. JS: Concatenates & uglifies Vendor and Custom JS files.
 *      4. Images: Minifies PNG, JPEG, GIF and SVG images.
 *      5. Watches files for changes in CSS or JS.
 *      6. Watches files for changes in PHP.
 *      7. Corrects the line endings.
 *      8. InjectCSS instead of browser page reload.
 *      9. Generates .pot file for i18n and l10n.
 *
 * @tutorial https://github.com/ahmadawais/WPGulp
 * @author Ahmad Awais <https://twitter.com/MrAhmadAwais/>
 */

/**
 * Load WPGulp Configuration.
 *
 * TODO: Customize your project in the wpgulp.js file.
 */
const config = require( './wpgulp.config.js' );

/**
 * Load Plugins.
 *
 * Load gulp plugins and passing them semantic names.
 */
const gulp = require( 'gulp' ); // Gulp of-course.

// CSS related plugins.
const sass = require( 'gulp-sass' ); // Gulp plugin for Sass compilation.
const minifycss = require( 'gulp-uglifycss' ); // Minifies CSS files.
const autoprefixer = require( 'gulp-autoprefixer' ); // Autoprefixing magic.
const mmq = require( 'gulp-merge-media-queries' ); // Combine matching media queries into one.
const rtlcss = require( 'gulp-rtlcss' ); // Generates RTL stylesheet.

// JS related plugins.
const concat = require( 'gulp-concat' ); // Concatenates JS files.
const uglify = require( 'gulp-uglify' ); // Minifies JS files.
const babel = require( 'gulp-babel' ); // Compiles ESNext to browser compatible JS.

// Image related plugins.
const imagemin = require( 'gulp-imagemin' ); // Minify PNG, JPEG, GIF and SVG images with imagemin.

// Utility related plugins.
const rename = require( 'gulp-rename' ); // Renames files E.g. style.css -> style.min.css.
const lineec = require( 'gulp-line-ending-corrector' ); // Consistent Line Endings for non UNIX systems. Gulp Plugin for Line Ending Corrector (A utility that makes sure your files have consistent line endings).
const filter = require( 'gulp-filter' ); // Enables you to work on a subset of the original files by filtering them using a glob.
const sourcemaps = require( 'gulp-sourcemaps' ); // Maps code in a compressed file (E.g. style.css) back to it’s original position in a source file (E.g. structure.scss, which was later combined with other css files to generate style.css).
const notify = require( 'gulp-notify' ); // Sends message notification to you.
const browserSync = require( 'browser-sync' ).create(); // Reloads browser and injects CSS. Time-saving synchronized browser testing.
const wpPot = require( 'gulp-wp-pot' ); // For generating the .pot file.
const sort = require( 'gulp-sort' ); // Recommended to prevent unnecessary changes in pot-file.
const cache = require( 'gulp-cache' ); // Cache files in stream for later use.
const remember = require( 'gulp-remember' ); //  Adds all the files it has ever seen back into the stream.
const plumber = require( 'gulp-plumber' ); // Prevent pipe breaking caused by errors from gulp plugins.
const beep = require( 'beepbeep' );
const merge = require( 'merge-stream' );
const defaults = require('lodash.defaults');

/**
 * Custom Error Handler.
 *
 * @param Mixed err
 */
const errorHandler = r => {
	notify.onError( '\n\n❌  ===> ERROR: <%= error.message %>\n' )( r );
	beep();

	// this.emit('end');
};

/**
 * Task: `browser-sync`.
 *
 * Live Reloads, CSS injections, Localhost tunneling.
 * @link http://www.browsersync.io/docs/options/
 *
 * @param {Mixed} done Done.
 */
const browsersync = done => {
	browserSync.init({
		proxy: config.projectURL,
		open: config.browserAutoOpen,
		injectChanges: config.injectChanges,
		watchEvents: [ 'change', 'add', 'unlink', 'addDir', 'unlinkDir' ]
	});
	done();
};

// Helper function to allow browser reload with Gulp 4.
const reload = done => {
	browserSync.reload();
	done();
};

/**
 * This function does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    3. Writes Sourcemaps for it
 *    4. Autoprefixes it and generates style.css
 *    5. Renames the CSS file with suffix .min.css
 *    6. Minifies the CSS file and generates style.min.css
 *    7. Injects CSS or reloads the browser via browserSync
 */
function processStyle( gulpStream, processOptions = {} ) {
	processOptions = defaults( processOptions, {
		styleDestination: config.styleDestination,
	} );

	return gulpStream
		.pipe( plumber( errorHandler ) )
		.pipe( sourcemaps.init() )
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			})
		)
		.on( 'error', sass.logError )
		.pipe( sourcemaps.write({ includeContent: false }) )
		.pipe( sourcemaps.init({ loadMaps: true }) )
		.pipe( autoprefixer( config.BROWSERS_LIST ) )
		.pipe( sourcemaps.write( './maps' ) )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( processOptions.styleDestination ) )
		.pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
		.pipe( mmq({ log: true }) ) // Merge Media Queries only for .min.css version.
		.pipe( browserSync.stream() ) // Reloads .css if that is enqueued.
		.pipe( rename({ suffix: '.min' }) )
		.pipe( minifycss({ maxLineLen: 10 }) )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( processOptions.styleDestination ) )
		.pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
		.pipe( browserSync.stream() ) // Reloads .min.css if that is enqueued.
		;
}

/**
 * This function does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    4. Autoprefixes it and generates style.css
 *    5. Renames the CSS file with suffix -rtl and generates style-rtl.css
 *    6. Writes Sourcemaps for style-rtl.css
 *    7. Renames the CSS files with suffix .min.css
 *    8. Minifies the CSS file and generates style-rtl.min.css
 *    9. Injects CSS or reloads the browser via browserSync
 */
function processStyleRTL( gulpStream, processOptions = {} ) {
	processOptions = defaults( processOptions, {
		styleDestination: config.styleDestination,
	} );

	return gulpStream
		.pipe( plumber( errorHandler ) )
		.pipe( sourcemaps.init() )
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			})
		)
		.on( 'error', sass.logError )
		.pipe( sourcemaps.write({ includeContent: false }) )
		.pipe( sourcemaps.init({ loadMaps: true }) )
		.pipe( autoprefixer( config.BROWSERS_LIST ) )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( rename({ suffix: '-rtl' }) ) // Append "-rtl" to the filename.
		.pipe( rtlcss() ) // Convert to RTL.
		.pipe( sourcemaps.write( './' ) ) // Output sourcemap for -rtl.css.
		.pipe( gulp.dest( processOptions.styleDestination ) )
		.pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
		.pipe( browserSync.stream() ) // Reloads .css or -rtl.css, if that is enqueued.
		.pipe( mmq({ log: true }) ) // Merge Media Queries only for .min.css version.
		.pipe( rename({ suffix: '.min' }) )
		.pipe( minifycss({ maxLineLen: 10 }) )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( processOptions.styleDestination ) )
		.pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
		.pipe( browserSync.stream() ) // Reloads .css or -rtl.css, if that is enqueued.
		;
}


/**
 * Task: `styles`.
 *
 * Compiles Sass, Autoprefixes it and Minifies CSS.
 *
 * This task does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    3. Writes Sourcemaps for it
 *    4. Autoprefixes it and generates CSS file
 *    5. Renames the CSS file with suffix .min.css
 *    6. Minifies the CSS file and generates .min.css
 *    7. Injects CSS or reloads the browser via browserSync
 */
gulp.task( 'styles', ( done ) => {
	// Exit task when no styles
	if ( config.styles.length === 0 ) {
		return done();
	}

	// Process each addon style
	var tasks = config.styles.map( function ( style ) {

		return processStyle(
			gulp.src( style.styleSRC, { allowEmpty: true }),
			{ styleDestination: style.styleDestination }
		).pipe( notify({ message: '\n\n✅  ===> STYLES — completed!\n', onLast: true }) );

	} );

	return merge( tasks );
});

/**
 * Task: `stylesRTL`.
 *
 * Compiles Sass, Autoprefixes it, Generates RTL stylesheet, and Minifies CSS.
 *
 * This task does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    4. Autoprefixes it and generates style.css
 *    5. Renames the CSS file with suffix -rtl and generates -rtl.css
 *    6. Writes Sourcemaps for -rtl.css
 *    7. Renames the CSS files with suffix .min.css
 *    8. Minifies the CSS file and generates -rtl.min.css
 *    9. Injects CSS or reloads the browser via browserSync
 */
gulp.task( 'stylesRTL', ( done ) => {
	// Exit task when no styles
	if ( config.styles.length === 0 ) {
		return done();
	}

	// Process each addon style
	var tasks = config.styles.map( function ( style ) {

		return	processStyleRTL(
			gulp.src( style.styleSRC, { allowEmpty: true }),
			{ styleDestination: style.styleDestination }
		).pipe( notify({ message: '\n\n✅  ===> STYLES RTL — completed!\n', onLast: true }) );

	} );

	return merge( tasks );
});

/**
 * Task: `scripts`.
 *
 * Compiles Sass, Autoprefixes it and Minifies CSS.
 *
 * This task does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    3. Writes Sourcemaps for it
 *    4. Autoprefixes it and generates CSS file
 *    5. Renames the CSS file with suffix .min.css
 *    6. Minifies the CSS file and generates .min.css
 *    7. Injects CSS or reloads the browser via browserSync
 */
gulp.task( 'scripts', ( done ) => {
	// Exit task when no scripts
	if ( config.scripts.length === 0 ) {
		return done();
	}

	// Process each addon style
	var tasks = config.scripts.map( function ( script ) {

		return processScript(
			gulp.src( script.scriptSRC, { allowEmpty: true }),
			{ scriptSRC: script.scriptSRC, scriptDestination: script.scriptDestination, scriptFile: script.scriptFile }
		).pipe( notify({ message: '\n\n✅  ===> SCRIPTS — completed!\n', onLast: true }) );

	} );

	return merge( tasks );
});

function processScript( gulpStream, processOptions = {} ) {
	processOptions = defaults( processOptions, {
		scriptDestination: config.scriptDestination,
	} );

	return gulpStream
		.pipe( plumber( errorHandler ) )
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env', // Preset to compile your modern JS to ES5.
						{
							targets: { browsers: config.BROWSERS_LIST } // Target browser list to support.
						}
					]
				]
			})
		)
		.pipe( remember( processOptions.scriptSRC ) ) // Bring all files back to stream.
		.pipe( concat( processOptions.scriptFile + '.js' ) )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( processOptions.scriptDestination ) )
		.pipe(
			rename({
				basename: processOptions.scriptFile,
				suffix: '.min'
			})
		)
		.pipe( uglify() )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( processOptions.scriptDestination ) );
}

/**
 * Task: `themeJS`.
 *
 * Concatenate and uglify vendor JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS vendor files
 *     2. Concatenates all the files and generates vendors.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates vendors.min.js
 */
gulp.task( 'themeJS', () => {
	return gulp
		.src( config.jsThemeSRC, { since: gulp.lastRun( 'themeJS' ) }) // Only run on changed files.
		.pipe( plumber( errorHandler ) )
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env', // Preset to compile your modern JS to ES5.
						{
							targets: { browsers: config.BROWSERS_LIST } // Target browser list to support.
						}
					]
				]
			})
		)
		.pipe( remember( config.jsThemeSRC ) ) // Bring all files back to stream.
		.pipe( concat( config.jsThemeFile + '.js' ) )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.jsThemeDestination ) )
		.pipe(
			rename({
				basename: config.jsThemeFile,
				suffix: '.min'
			})
		)
		.pipe( uglify() )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.jsThemeDestination ) )
		.pipe( notify({ message: '\n\n✅  ===> THEME JS — completed!\n', onLast: true }) );
});

/**
 * Task: `adminJS`.
 *
 * Concatenate and uglify custom JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS custom files
 *     2. Concatenates all the files and generates custom.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates custom.min.js
 */
gulp.task( 'adminJS', () => {
	return gulp
		.src( config.jsAdminSRC, { since: gulp.lastRun( 'adminJS' ) }) // Only run on changed files.
		.pipe( plumber( errorHandler ) )
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env', // Preset to compile your modern JS to ES5.
						{
							targets: { browsers: config.BROWSERS_LIST } // Target browser list to support.
						}
					]
				]
			})
		)
		.pipe( remember( config.jsAdminSRC ) ) // Bring all files back to stream.
		.pipe( concat( config.jsAdminFile + '.js' ) )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.jsAdminDestination ) )
		.pipe(
			rename({
				basename: config.jsAdminFile,
				suffix: '.min'
			})
		)
		.pipe( uglify() )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.jsAdminDestination ) )
		.pipe( notify({ message: '\n\n✅  ===> ADMIN JS — completed!\n', onLast: true }) );
});

/**
 * Task: `images`.
 *
 * Minifies PNG, JPEG, GIF and SVG images.
 *
 * This task does the following:
 *     1. Gets the source of images raw folder
 *     2. Minifies PNG, JPEG, GIF and SVG images
 *     3. Generates and saves the optimized images
 *
 * This task will run only once, if you want to run it
 * again, do it with the command `gulp images`.
 *
 * Read the following to change these options.
 * @link https://github.com/sindresorhus/gulp-imagemin
 */
gulp.task( 'images', () => {
	return gulp
		.src( config.imgSRC )
		.pipe(
			cache(
				imagemin([
					imagemin.gifsicle({ interlaced: true }),
					imagemin.jpegtran({ progressive: true }),
					imagemin.optipng({ optimizationLevel: 3 }), // 0-7 low-high.
					imagemin.svgo({
						plugins: [ { removeViewBox: true }, { cleanupIDs: false } ]
					})
				])
			)
		)
		.pipe( gulp.dest( config.imgDST ) )
		.pipe( notify({ message: '\n\n✅  ===> IMAGES — completed!\n', onLast: true }) );
});

/**
 * Task: `clear-images-cache`.
 *
 * Deletes the images cache. By running the next "images" task,
 * each image will be regenerated.
 */
gulp.task( 'clearCache', function( done ) {
	return cache.clearAll( done );
});

/**
 * WP POT Translation File Generator.
 *
 * This task does the following:
 * 1. Gets the source of all the PHP files
 * 2. Sort files in stream by path or any custom sort comparator
 * 3. Applies wpPot with the variable set at the top of this file
 * 4. Generate a .pot file of i18n that can be used for l10n to build .mo file
 */
gulp.task( 'translate', () => {
	return gulp
		.src( config.watchPhp )
		.pipe( sort() )
		.pipe(
			wpPot({
				domain: config.textDomain,
				package: config.packageName,
				bugReport: config.bugReport,
				lastTranslator: config.lastTranslator,
				team: config.team
			})
		)
		.pipe( gulp.dest( config.translationDestination + '/' + config.translationFile ) )
		.pipe( notify({ message: '\n\n✅  ===> TRANSLATE — completed!\n', onLast: true }) );
});

/**
 * Watch Tasks.
 *
 * Watches for file changes and runs specific tasks.
 */
gulp.task(
	'default',
	gulp.parallel( 'styles', 'themeJS', 'adminJS', 'images', browsersync, () => {
		gulp.watch( config.watchPhp, reload ); // Reload on PHP file changes.
		gulp.watch( config.watchStyles, gulp.parallel( 'styles' ) ); // Reload on SCSS file changes.
		gulp.watch( config.watchJsTheme, gulp.series( 'themeJS', reload ) ); // Reload on themeJS file changes.
		gulp.watch( config.watchJsAdmin, gulp.series( 'adminJS', reload ) ); // Reload on adminJS file changes.
		gulp.watch( config.imgSRC, gulp.series( 'images', reload ) ); // Reload on adminJS file changes.
	})
);

const zip = require( 'gulp-zip' ),
	buildInclude = [ '**/*', '!package*.json', '!./{node_modules,node_modules/**/*}', '!./{dist,dist/**/*}', '!./{assets/css,assets/scss/**/*}', '!./{assets/js,assets/js/**/*}' ],
	packageJSON = require( './package.json' ),
	fileName = packageJSON.name,
	fileVersion = packageJSON.version;

/**
 * Used to generate the plugin zip file that will be uploaded the Freemius.
 * Generates a zip file based on the name and version found within the package.json
 *
 * usage : gulp zip
 *
 */
gulp.task(
	'zip', function() {
		return gulp.src( buildInclude, {base: './'})
			.pipe( zip( fileName + '.v' + fileVersion + '.zip' ) )
			.pipe( gulp.dest( 'dist/' ) )
			.pipe( notify({message: 'Zip task complete', onLast: true}) );
	}
);

const fsConfig = require( './fs-config.json' );

/**
 * Used to deploy a version to your Freemius dashboard.
 * Gets info from fs-config.json file.
 * IMPORTANT : make sure fs-config.json is added to .gitignore, as it contains sensitive info about your Freemius account
 * Posts the latest zip in /dist to the Freemius deployment API
 *
 * usage : gulp freemius-deploy
 */
require( 'gulp-freemius-deploy' )( gulp, {
	developer_id: fsConfig.developer_id,
	plugin_id: fsConfig.plugin_id,
	public_key: fsConfig.public_key,
	secret_key: fsConfig.secret_key,
	zip_name: fileName + '.v' + fileVersion + '.zip',
	zip_path: 'dist/',
	add_contributor: true
});

