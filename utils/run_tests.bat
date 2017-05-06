@echo OFF
set PATH=%PATH%;%~pd0..\public_html\include\vendor\bin

cd ..\public_html\include\
phpunit --no-coverage ..\..\tests\MZ\
cd %~pd0
