@echo OFF
set PATH=%PATH%;%~pd0..\public_html\include\vendor\bin

cd ..
phpcs --standard=psr2 public_html\include\api\MZ
cd %~pd0
