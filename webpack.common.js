const path = require('path');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const buildDir = './dist/';

module.exports = {
	entry: {
		main: [
			'./src/js/main.js',
		],
		'editor-style': [
			'./src/js/editor-style.js',
		]
	},
	output: {
		filename: 'js/[name].[chunkhash:8].js',
		path: path.join(__dirname, buildDir)
	},
	stats: {
		assets: false,
		modules: false,
		entrypoints: false,
		hash: false
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules\/(?!(@webcomponents\/shadycss|lit-html|@polymer|@lit)\/).*/,
				loader: 'babel-loader'
			},
			{
				test: /\.s?css$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
						options: {
							//sourceMap: true,
							minimize: true
						}
					},
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						}
					},
					{
						loader: 'postcss-loader',
						options: {
							sourceMap: true
						}
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: true
						}
					},
				],
			},
			{
				test: /\/src\/images\/.+\.(svg|png|gif|jpe?g)$/,// jpg doesn't work with the img-loader
				loaders: [
					{
						loader: 'file-loader',
						options: {
							name: 'images/[name].[hash:8].[ext]'
						}
					},
					{
						loader: 'img-loader',
						options: {
							svgo: {
								plugins: [
									{ removeViewBox: false },
									{ inlineStyles: false }
								]
							},
							mozjpeg: false
						}
					}
				]
			},
			{
				test: /screenshot\.png$/,
				loaders: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]'
						}
					},
					'img-loader'
				]
			}
		]
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: "[name].[contenthash:8].css"
		}),
		new CleanWebpackPlugin({
			cleanOnceBeforeBuildPatterns: ['**/*', '!vendor*', '!vendor/**/*'],// beter to solve this by introducing composer to webpack
			cleanStaleWebpackAssets: false
		}),
		new CopyWebpackPlugin([
			{
				context: 'src',
				from: '**/*.php',
			},
			{
				context: 'src',
				from: '*.css',
			},
		]),
		function(){
			this.plugin('done', stats => {
				require('fs').writeFileSync(
					path.join(__dirname, buildDir + 'manifest.json'),
					JSON.stringify(stats.toJson().assets)
				);
			})
		},
	]
};