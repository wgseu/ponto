@echo OFF
SET OLD_PATH=%PATH%
set PATH=%PATH%;%~pd0..\public\include\vendor\bin

cd ..
phpunit --coverage-html %~pd0tmp/coverage
cd %~pd0
cmd /c start %~pd0tmp/coverage/index.html

SET PATH=%OLD_PATH%
