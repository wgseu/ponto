@echo OFF

cat index.js > main.js
if NOT %ERRORLEVEL% == 0 goto error
cat bootstrap.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat SmoothScroll.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat carousel-swipe.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat jarallax.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat masonry.pkgd.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat imagesloaded.pkgd.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat social-likes.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat script.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat script.gallery.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.maskedinput.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat base64.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat switchery.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.thunder.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat accordion.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.vmap.js >> main.js
if NOT %ERRORLEVEL% == 0 goto error
java -jar packer\yuicompressor.jar main.js -o ..\js\main.min.js --charset utf-8
if NOT %ERRORLEVEL% == 0 goto error
echo Output: ..\js\main.min.js
del main.js

cat bootstrap.js > manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat custom.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat shortcut.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.nicescroll.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat auto.numeric.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.maskedinput.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.datetimepicker.full.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.autocomplete.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat simplebar.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat starrr.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat kc.fab.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
cat jquery.thunder.js >> manager.js
if NOT %ERRORLEVEL% == 0 goto error
java -jar packer\yuicompressor.jar manager.js -o ..\js\manager.min.js --charset utf-8
if NOT %ERRORLEVEL% == 0 goto error
echo Output: ..\js\manager.min.js
del manager.js

java -jar packer\yuicompressor.jar index.js -o ..\js\index.min.js --charset utf-8
if NOT %ERRORLEVEL% == 0 goto error
echo Output: ..\js\index.min.js

goto success

:error
echo Failed with error %ERRORLEVEL%
:success
