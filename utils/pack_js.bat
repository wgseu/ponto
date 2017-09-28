@echo off

cat %~dp0..\src\static\jssrc\jquery.thunder.js > index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\diacritics.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\auto.numeric.min.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery.datetimepicker.full.min.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\bootstrap.min.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery.easing.min.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\classie.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\cbpAnimatedHeader.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery.maskedinput.min.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\agency.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\index.js >> index.js
if NOT %ERRORLEVEL% == 0 goto error
java -jar packer\yuicompressor.jar index.js -o ..\src\static\js\index.min.js --charset utf-8
if NOT %ERRORLEVEL% == 0 goto error
echo Output: src\static\js\index.min.js
del index.js

cat %~dp0..\src\static\jssrc\jquery.thunder.js > manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\gauge\gauge.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\moment\moment.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\chartjs\chart.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\progressbar\bootstrap-progressbar.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\icheck\icheck.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\datepicker\daterangepicker.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\jquery.flot.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\jquery.flot.pie.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\jquery.flot.orderBars.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\jquery.flot.time.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\date.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\jquery.flot.spline.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\jquery.flot.stack.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\curvedLines.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\flot\jquery.flot.resize.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\diacritics.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\switchery\switchery.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\nicescroll\jquery.nicescroll.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\custom.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\bootstrap.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\auto.numeric.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\inputmask.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery.datetimepicker.full.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery-ui\jquery-ui.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery.maskedinput.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery.autocomplete.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\simplebar.min.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\jquery.ddslick.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\raphael.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\Treant.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat %~dp0..\src\static\jssrc\index.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
java -jar packer\yuicompressor.jar manager.js -o ..\src\static\js\manager.min.js --charset utf-8
if NOT %ERRORLEVEL% == 0 goto error
echo Output: src\static\js\manager.min.js
del manager.js

goto success

:error
echo Failed with error %ERRORLEVEL%
:success
