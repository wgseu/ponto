@echo OFF
set PATH=%PATH%;%~pd0..\public_html\include\vendor\bin

cd ..
phpmd public_html\include\api text cleancode,codesize,controversial,design,unusedcode > utils\tmp\analisys.txt
cd %~pd0
