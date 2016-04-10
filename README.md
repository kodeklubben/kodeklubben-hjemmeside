# Generic website for Kodeklubben

## Requirements
- [PHP](http://php.net/)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/en/)

## Install
`composer install`

`composer build`

### Build problems
Try deleting `node_modules` before running `composer build`.

Windows has issues with long filenames which may prevent you from deleting the `node_modules` directory. To solve this, run

`npm install -g rimraf`

and then remove by executing

`rimraf node_modules`
