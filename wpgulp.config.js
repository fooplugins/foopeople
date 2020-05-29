/**
 * WPGulp Configuration File
 *
 * 1. Edit the variables as per your project requirements.
 * 2. In paths you can add <<glob or array of globs>>.
 *
 * @package WPGulp
 */

module.exports = {

	// Project options.
	projectURL: 'http://127.0.0.1/', // Local project URL of your already running WordPress site. Could be something like wpgulp.local or localhost:3000 depending upon your local WordPress setup.
	productURL: './', // Theme/Plugin URL. Leave it like it is, since our gulpfile.js lives in the root folder.
	browserAutoOpen: false,
	injectChanges: true,

	// Style options.
	styleDestination: './assets/css', // Default path to place the compiled CSS file. Default set to root folder.
	outputStyle: 'compact', // Available options → 'compact' or 'compressed' or 'nested' or 'expanded'
	errLogToConsole: true,
	precision: 10,

	// The following list is a set of SCSS/CSS files which you want to process and place it on a different folder.
	// Please see README.md for usage.
	styles: [
		{
			styleSRC: './assets/scss/admin.scss', // Path to .scss file.
			styleDestination: './assets/css', // Path to place the compiled CSS file. Default set to root folder.
		},
		{
			styleSRC: './assets/scss/foometafields.scss', // Path to .scss file.
			styleDestination: './assets/css', // Path to place the compiled CSS file. Default set to root folder.
		},
	],

	// Script options.
	scriptDestination: './assets/js/',

	scripts: [
		{
			scriptSRC: './assets/scripts/vendor/*.js', // Path to .js file.
			scriptDestination: './assets/js/', // Path to place the compiled JS file.
			scriptFile: 'vendor'
		},
		{
			scriptSRC: './assets/scripts/custom/*.js', // Path to .js file.
			scriptDestination: './assets/js/', // Path to place the compiled JS file.
			scriptFile: 'custom'
		},
		{
			scriptSRC: './assets/scripts/foometafields.js', // Path to .js file.
			scriptDestination: './assets/js/', // Path to place the compiled JS file.
			scriptFile: 'foometafields'
		},
	],

	// JS Vendor options.
	jsVendorSRC: './assets/scripts/vendor/*.js', // Path to JS vendor folder.
	jsVendorDestination: './assets/js/', // Path to place the compiled JS vendors file.
	jsVendorFile: 'vendor', // Compiled JS vendors file name. Default set to vendors i.e. vendors.js.

	// JS Custom options.
	jsCustomSRC: './assets/scripts/custom/*.js', // Path to JS custom scripts folder.
	jsCustomDestination: './assets/js/', // Path to place the compiled JS custom scripts file.
	jsCustomFile: 'custom', // Compiled JS custom file name. Default set to custom i.e. custom.js.

	// Images options.
	imgSRC: './assets/img/raw/**/*', // Source folder of images which should be optimized and watched. You can also specify types e.g. raw/**.{png,jpg,gif} in the glob.
	imgDST: './assets/img/', // Destination folder of optimized images. Must be different from the imagesSRC folder.

	// Watch files paths.
	watchStyles: './assets/scss/**/*.scss', // Path to all *.scss files inside css folder and inside them.
	watchScripts: './assets/scripts/**/*.js', // Path to all *.js files inside scripts folder and inside them.
	watchPhp: './**/*.php', // Path to all PHP files.

	// Translation options.
	textDomain: 'foopeople', // Your textdomain here.
	translationFile: 'foopeople.pot', // Name of the translation file.
	translationDestination: './languages', // Where to save the translation files.
	packageName: 'FooPeople', // Package name.
	bugReport: 'https://fooplugins.com', // Where can users report bugs.
	lastTranslator: 'Brad Vincent <brad@fooplugins.com>', // Last translator Email ID.
	team: 'Brad Vincent <brad@fooplugins.com', // Team's Email ID.

	// Browsers you care about for autoprefixing. Browserlist https://github.com/ai/browserslist
	// The following list is set as per WordPress requirements. Though, Feel free to change.
	BROWSERS_LIST: [
		'last 2 version',
		'> 1%',
		'ie >= 11',
		'last 1 Android versions',
		'last 1 ChromeAndroid versions',
		'last 2 Chrome versions',
		'last 2 Firefox versions',
		'last 2 Safari versions',
		'last 2 iOS versions',
		'last 2 Edge versions',
		'last 2 Opera versions'
	]
};
