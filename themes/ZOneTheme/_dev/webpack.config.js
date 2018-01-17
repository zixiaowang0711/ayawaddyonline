var webpack = require('webpack');
var path = require('path');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

var plugins = [];
var devTool = 'source-map';

var production = false;

if (production) {
  plugins.push(
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: false,
      compress: {
        warnings: false
      },
      output: {
        comments: false
      },
      minimize: true
    })
  );
  devTool = '';
}

plugins.push(
  new ExtractTextPlugin(
    path.join(
      '..', 'css', 'theme.css'
    )
  )
);

module.exports = {
  entry: [
    './js/theme.js'
  ],
  output: {
    path: '../assets/js',
    filename: 'theme.js'
  },
  module: {
    loaders: [{
      test: /\.js$/,
      exclude: /node_modules/,
      loaders: ['babel-loader']
    }, {
      test: /\.scss$/,
      loader: ExtractTextPlugin.extract(
        "style",
        "css?sourceMap!postcss!sass?sourceMap=true&precision=7"
      )
    }, {
      test: /.(gif|jpg|png|woff(2)?|eot|ttf|svg)(\?[a-z0-9=\.]+)?$/,
      loader: 'file-loader?name=../css/[hash].[ext]'
    }, {
      test: /\.css$/,
      loader: "style-loader!css-loader!postcss-loader"
    }]
  },
  postcss: function() {
    return [require('postcss-flexibility')];
  },
  externals: {
    prestashop: 'prestashop',
    $: '$',
    jquery: 'jQuery'
  },
  devtool: devTool,
  plugins: plugins,
  resolve: {
    extensions: ['', '.js', '.scss']
  }
};
