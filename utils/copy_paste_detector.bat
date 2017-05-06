@echo OFF
set PATH=%PATH%;%~pd0..\public_html\include\vendor\bin

cd ..
phpcpd public_html\include\api
cd %~pd0
