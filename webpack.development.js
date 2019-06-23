const merge = require('webpack-merge');
const common = require('./webpack.common.js');

const exec = require('child_process').exec

module.exports = merge(common, {
	mode: 'development',
	devtool: 'source-map',
	//devtool: 'eval-source-map',
});