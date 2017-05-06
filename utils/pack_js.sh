#!/bin/sh

php $(pwd)/packer/example-file.php index.js index.min.js
cp -rf datepicker.js ../js/datepicker.min.js
