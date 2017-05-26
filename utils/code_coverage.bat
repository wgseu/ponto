@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpunit --coverage-html %~pd0tmp/coverage
cd %~pd0
cmd /c start %~pd0tmp/coverage/index.html
