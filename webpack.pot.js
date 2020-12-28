const path = require('path');
const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const buildDir = './web/app/themes/judo-losser';

module.exports = merge(common, {
	mode: 'development',
	devtool: 'source-map',
	output: {
		filename: 'js/[name].js',
		path: path.join(__dirname, buildDir)
	}
});