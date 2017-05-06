@echo OFF

REM Site principal
cat font-roboto.css  > index.css
if NOT %ERRORLEVEL% == 0 goto error
cat bootstrap.css  >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat font-awesome.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat animate.min.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat socicon.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat socicon-bg.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat style.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat style-slider.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat style-gallery.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat mbr-additional.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat switchery.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat common.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.thunder.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat tabs.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat tabstyles.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat faq.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
cat jqvmap.css >> index.css
if NOT %ERRORLEVEL% == 0 goto error
java -jar ..\jssrc\packer\yuicompressor.jar index.css -o ..\css\index.min.css --charset utf-8
if NOT %ERRORLEVEL% == 0 goto error
echo Output: ..\css\index.min.css
del index.css

REM Gerenciamento
cat bootstrap.css  > manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat font-awesome.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat animate.min.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat custom.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat common.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat simplebar.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat starrr.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat kc.fab.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.datetimepicker.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.thunder.css >> manager.css
if NOT %ERRORLEVEL% == 0 goto error
java -jar ..\jssrc\packer\yuicompressor.jar manager.css -o ..\css\manager.min.css --charset utf-8
if NOT %ERRORLEVEL% == 0 goto error
echo Output: ..\css\manager.min.css
del manager.css

goto success

:error
echo Failed with error %ERRORLEVEL%
:success