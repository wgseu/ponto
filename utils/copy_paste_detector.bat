@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpcpd src\include\api
cd %~pd0
