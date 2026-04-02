const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const path = require( 'path' );

const EXCLUDE_PLUGINS = new Set( [
	'DependencyExtractionWebpackPlugin',
	'PhpFilePathsPlugin', // scans PHP for entry discovery, not needed here
] );

const plugins = defaultConfig.plugins
	.filter( ( p ) => ! EXCLUDE_PLUGINS.has( p.constructor.name ) )
	.concat(
		new DependencyExtractionWebpackPlugin( {
			requestToExternal( request ) {
				if ( request === '@wordpress/dataviews' ) {
					return false; // bundle it, do not externalize
				}
				// undefined → use default wp.* global mapping for everything else
			},
		} )
	);

module.exports = {
	...defaultConfig,
	entry: {
		'style-picker': path.resolve( __dirname, 'admin/js/artp-style-picker.js' ),
	},
	output: {
		path: path.resolve( __dirname, 'admin/js/build' ),
		filename: '[name].js',
	},
	plugins,
};
