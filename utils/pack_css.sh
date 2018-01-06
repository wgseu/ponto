#!/bin/sh

# Site principal
cat ../resources/assets/css/bootstrap.css  > index.bundle.css
cat ../resources/assets/css/agency.css >> index.bundle.css
cat ../resources/assets/css/index.css >> index.bundle.css
cat ../resources/assets/css/font-awesome.css >> index.bundle.css
cat ../resources/assets/css/jquery.datetimepicker.css >> index.bundle.css
cat ../resources/assets/css/jquery.thunder.css >> index.bundle.css
java -jar packer/yuicompressor.jar index.bundle.css -o ../public/static/css/index.min.css --charset utf-8
rm -f index.bundle.css

# Gerenciamento
cat ../resources/assets/css/bootstrap.css  > manager.bundle.css
cat ../resources/assets/css/font-awesome.css >> manager.bundle.css
cat ../resources/assets/css/animate.css >> manager.bundle.css
cat ../resources/assets/css/index.css >> manager.bundle.css
cat ../resources/assets/css/custom.css >> manager.bundle.css
cat ../resources/assets/css/switchery/switchery.css >> manager.bundle.css
cat ../resources/assets/css/icheck/flat/green.css >> manager.bundle.css
cat ../resources/assets/css/floatexamples.css >> manager.bundle.css
cat ../resources/assets/css/simplebar.css >> manager.bundle.css
cat ../resources/assets/css/daterangepicker.css >> manager.bundle.css
cat ../resources/assets/css/jquery.datetimepicker.css >> manager.bundle.css
cat ../resources/assets/css/Treant.css >> manager.bundle.css
cat ../resources/assets/css/jquery.thunder.css >> manager.bundle.css
java -jar packer/yuicompressor.jar manager.bundle.css -o ../public/static/css/manager.min.css --charset utf-8
rm -f manager.bundle.css
