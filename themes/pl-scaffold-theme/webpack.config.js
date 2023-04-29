const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
const path = require('path');

module.exports = (env, argv) => {
    const mode = argv.mode || 'development';

    return {
        mode: mode,
        entry: {
            'frontend': ['./assets/css/frontend.css', './assets/js/frontend.js'],
            'backend': ['./assets/css/backend.css', './assets/js/backend.js'],
        },
        output: {
            path: path.resolve(__dirname, 'dist'),
            filename: '[name].js',
        },
        module: {
            rules: [
                {
                    test: /\.css$/,
                    use: [MiniCssExtractPlugin.loader, 'css-loader'],
                },
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                },
            ],
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: '[name].css',
            }),
            new IgnoreEmitPlugin(/\.js$/),
        ],
    };
};
