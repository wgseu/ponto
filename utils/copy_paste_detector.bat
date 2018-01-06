@echo OFF
SET OLD_PATH=%PATH%
set PATH=%PATH%;%~pd0..\public\include\vendor\bin

cd ..
phpcpd public\include\api
cd %~pd0

SET PATH=%OLD_PATH%
