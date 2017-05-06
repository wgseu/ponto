@echo OFF
set PATH=%PATH%;%~pd0..\public_html\include\vendor\bin

cd ..\public_html\include\
phpunit --coverage-html %~pd0tmp/coverage
cd %~pd0
cmd /c start %~pd0tmp/coverage/index.html
