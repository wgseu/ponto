@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpmd src\include\api text cleancode,codesize,controversial,design,unusedcode > utils\tmp\analisys.txt
cd %~pd0
