@echo OFF
set PATH=%PATH%;%~pd0..\public_html\include\vendor\bin

cd ..
phpcbf --no-patch --standard=psr2 public_html\include\api\
cd %~pd0
