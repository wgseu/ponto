@echo OFF

cd ..\public_html\include\

composer dump-autoload

cd %~pd0
