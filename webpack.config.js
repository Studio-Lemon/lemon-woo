// create webpack config that compiles the resources/assets/app.scss file
// into the dist/css/app.css file
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
   entry: [
      './resources/assets/styles/app.scss',
      './resources/assets/scripts/app.js',
   ],
   output: {
      path: path.resolve(__dirname, 'dist'),
   },
   module: {
      rules: [
         {
            test: /\.scss$/,
            use: [
               MiniCssExtractPlugin.loader,
               'css-loader',
               'sass-loader',
            ],
         },
      ],
   },
   plugins: [
      new MiniCssExtractPlugin({
         filename: 'app.css',
      }),
   ],
};