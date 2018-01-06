@echo OFF
SET OLD_PATH=%PATH%
set PATH=%PATH%;%~pd0..\public\include\vendor\bin

cd ..
phpmd public\include\api text cleancode,codesize,controversial,design,unusedcode > utils\tmp\analisys.txt
cd %~pd0

SET PATH=%OLD_PATH%
