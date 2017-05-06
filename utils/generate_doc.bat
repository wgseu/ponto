@echo OFF
set PATH=%PATH%;%~pd0..\public_html\include\vendor\bin

cd ..
phpdoc --progressbar --sourcecode -d public_html\include\api\MZ -t docs\api
cd %~pd0
start %~pd0..\docs\api\index.html
