@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpcbf --no-patch --standard=psr2 src\include\classes\
cd %~pd0
