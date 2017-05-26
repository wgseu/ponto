@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpcs --standard=psr2 src\include\api
cd %~pd0
