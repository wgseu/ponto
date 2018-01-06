@echo off

REM Site principal
cat ../resources/assets/js/jquery.thunder.js > index.bundle.js
cat ../resources/assets/js/diacritics.js >> index.bundle.js
cat ../resources/assets/js/auto.numeric.min.js >> index.bundle.js
cat ../resources/assets/js/jquery.datetimepicker.full.min.js >> index.bundle.js
cat ../resources/assets/js/bootstrap.min.js >> index.bundle.js
cat ../resources/assets/js/jquery.easing.min.js >> index.bundle.js
cat ../resources/assets/js/classie.js >> index.bundle.js
cat ../resources/assets/js/cbpAnimatedHeader.js >> index.bundle.js
cat ../resources/assets/js/jquery.maskedinput.min.js >> index.bundle.js
cat ../resources/assets/js/agency.js >> index.bundle.js
cat ../resources/assets/js/index.js >> index.bundle.js
java -jar packer/yuicompressor.jar index.bundle.js -o ../public/static/js/index.min.js --charset utf-8
rm -f index.bundle.js

REM Gerenciamento
cat ../resources/assets/js/jquery.thunder.js > manager.bundle.js
cat ../resources/assets/js/gauge/gauge.min.js >> manager.bundle.js
cat ../resources/assets/js/moment/moment.min.js >> manager.bundle.js
cat ../resources/assets/js/chartjs/chart.min.js >> manager.bundle.js
cat ../resources/assets/js/progressbar/bootstrap-progressbar.min.js >> manager.bundle.js
cat ../resources/assets/js/icheck/icheck.min.js >> manager.bundle.js
cat ../resources/assets/js/datepicker/daterangepicker.js >> manager.bundle.js
cat ../resources/assets/js/flot/jquery.flot.js >> manager.bundle.js
cat ../resources/assets/js/flot/jquery.flot.pie.js >> manager.bundle.js
cat ../resources/assets/js/flot/jquery.flot.orderBars.js >> manager.bundle.js
cat ../resources/assets/js/flot/jquery.flot.time.min.js >> manager.bundle.js
cat ../resources/assets/js/flot/date.js >> manager.bundle.js
cat ../resources/assets/js/flot/jquery.flot.spline.js >> manager.bundle.js
cat ../resources/assets/js/flot/jquery.flot.stack.js >> manager.bundle.js
cat ../resources/assets/js/flot/curvedLines.js >> manager.bundle.js
cat ../resources/assets/js/flot/jquery.flot.resize.js >> manager.bundle.js
cat ../resources/assets/js/diacritics.js >> manager.bundle.js
cat ../resources/assets/js/switchery/switchery.js >> manager.bundle.js
cat ../resources/assets/js/nicescroll/jquery.nicescroll.min.js >> manager.bundle.js
cat ../resources/assets/js/custom.js >> manager.bundle.js
cat ../resources/assets/js/bootstrap.min.js >> manager.bundle.js
cat ../resources/assets/js/auto.numeric.min.js >> manager.bundle.js
cat ../resources/assets/js/inputmask.min.js >> manager.bundle.js
cat ../resources/assets/js/jquery.datetimepicker.full.min.js >> manager.bundle.js
cat ../resources/assets/js/jquery-ui/jquery-ui.js >> manager.bundle.js
cat ../resources/assets/js/jquery.maskedinput.min.js >> manager.bundle.js
cat ../resources/assets/js/jquery.autocomplete.min.js >> manager.bundle.js
cat ../resources/assets/js/simplebar.min.js >> manager.bundle.js
cat ../resources/assets/js/jquery.ddslick.js >> manager.bundle.js
cat ../resources/assets/js/raphael.js >> manager.bundle.js
cat ../resources/assets/js/Treant.js >> manager.bundle.js
cat ../resources/assets/js/index.js >> manager.bundle.js
java -jar packer/yuicompressor.jar manager.bundle.js -o ../public/static/js/manager.min.js --charset utf-8
rm -f manager.bundle.js
