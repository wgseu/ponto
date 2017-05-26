@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpdoc --progressbar --sourcecode -d src\include\api\MZ -t docs\api
cd %~pd0
start %~pd0..\docs\api\index.html
