# Generic website for Kodeklubben

## Requirements
- [PHP](http://php.net/)
- [Composer](https://nodejs.org/en/)
- [Node.js](https://getcomposer.org/)

## Install
`composer install`

`composer build`

### Build problems
Try deleting `node_modules` before running `composer build`.

Windows has issues with long filenames which may prevent you from deleting the `node_modules` directory. To solve this, run

`npm install -g rimraf`

and then remove by executing

`rimraf node_modules`
