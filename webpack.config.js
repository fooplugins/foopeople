const wplib = [
	'blocks',
	'block-editor',
	'components',
	'compose',
	'date',
	'data',
	'editor',
	'element',
	'i18n',
	'polyfill'
];

module.exports = {
	entry: {
		listing: './assets/scripts/blocks/block-listing.js',
		single: './assets/scripts/blocks/block-single.js'
	},
	output: {
		path: __dirname + '/assets/js',
		filename: 'block-[name].min.js',
		library: [ 'wp', '[name]' ],
		libraryTarget: 'window'
	},

	// mode: 'development', // DO NOT Enable this, files do not compile
	externals: wplib.reduce( ( externals, lib ) => {
		externals[`wp.${lib}`] = {
			window: [ 'wp', lib ]
		};
		return externals;
	}, {}),

	module: {
		rules: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/
			}
		]
	}
};
