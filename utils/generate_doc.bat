@echo OFF
SET OLD_PATH=%PATH%
set PATH=%PATH%;%~pd0..\public\include\vendor\bin

cd ..
phpdoc --progressbar --sourcecode -d public\include\api\MZ -t docs\api
cd %~pd0
start %~pd0..\docs\api\index.html

SET PATH=%OLD_PATH%
