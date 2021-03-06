const path = require('path');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');

const gettextParser = require('gettext-parser');

const buildDir = './web/app/themes/judo-losser';

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
			{
				context: 'src',
				from: '.htaccess',
			}
		]),
		function() {
			// generate mo files from po files
			this.plugin('done', () => {
				const fs = require('fs');
				const glob = require('glob');

				const poFiles = glob.sync('./src/languages/*.po');

				const sourcePath = path.join(__dirname, './web/app/themes/judo-losser/languages');

				if (!fs.existsSync(sourcePath)){
					fs.mkdirSync(sourcePath);
				}

				// compile to mo files and write in build dir
				poFiles.forEach(filePath => {
					const file = fs.readFileSync(path.join(__dirname, filePath));
					const fileName = path.basename(filePath, path.extname(filePath));

					const output = gettextParser.mo.compile(gettextParser.po.parse(file));
					fs.writeFileSync(path.join(sourcePath, fileName + '.mo'), output);
				});
			});
		},
		new WebpackManifestPlugin()
	]
};