const webpack = require('webpack');
const path = require('path');

const ExtractTextPlugin = require("extract-text-webpack-plugin");
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const themeName = 'judolosser';
const distDir = './wordpress/wp-content/themes/' + themeName + '/';
const inProduction = (process.env.NODE_ENV === 'production');

const extractMainStyle = new ExtractTextPlugin('main.[contenthash:6].css');
const extractEditorStyle = new ExtractTextPlugin('editor-style.[contenthash:6].css');
const extractWpStyle = new ExtractTextPlugin('style.css');


module.exports = {
	entry: {
		main: [
			'./src/js/main.js',
		],
	},
	output: {
		filename: 'js/[name].[chunkhash:6].js',
		path: path.join(__dirname, distDir)
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: "babel-loader"
			},
			{
				test: /\.css$/,
				exclude: /\/(style|editor-style)\.scss$/,
				use: extractMainStyle.extract({
					use: 'css-loader',
					fallback: 'style-loader'
				})
			},
			{
				test: /\.scss$/,
				exclude: /\/(style|editor-style)\.scss$/,
				use: extractMainStyle.extract({
					use: ['css-loader', 'sass-loader'],
					fallback: 'style-loader'
				})
			},
			{
				test: /\/style\.scss$/,
				use: extractWpStyle.extract({
					use: ['css-loader', 'sass-loader'],
					fallback: 'style-loader'
				})
			},
			{
				test: /\/editor-style\.scss$/,
				use: extractEditorStyle.extract({
					use: ['css-loader', 'sass-loader'],
					fallback: 'style-loader'
				})
			},
			{
				test: /\.(svg|png|gif)$/,// jpg doesn't work with the img-loader
				exclude: /screenshot.png$/,
				loaders: [
					{
						loader: 'file-loader',
						options: {
							name: 'images/[name].[hash:6].[ext]'
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
				test: /\.jpe?g$/,
				loaders: [
					{
						loader: 'file-loader',
						options: {
							name: 'images/[name].[hash:6].[ext]'
						}
					},
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
			},
		]
	},
	plugins: [
		new CleanWebpackPlugin(
			distDir,
			{
				root: __dirname,
				verbose: true,
				dry: false
			}
		),
		extractMainStyle,
		extractWpStyle,
		extractEditorStyle,
		new webpack.LoaderOptionsPlugin({
			minimize: inProduction
		}),
		new CopyWebpackPlugin([
			{
				context: 'src',
				from: '**/*.php',
			}
		]),
		function(){
			this.plugin('done', stats => {
				require('fs').writeFileSync(
					path.join(__dirname, distDir + 'manifest.json'),
					JSON.stringify(stats.toJson().assets)
				);
			})
		},
	]
};