@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpunit --no-coverage tests\
cd %~pd0
