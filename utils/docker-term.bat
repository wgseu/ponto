@echo off
set CURDIR=%~1
set UNIXDIR=/%CURDIR:\=/%
set UNIXDIR=%UNIXDIR::=%
set OLD_CD=%~d1
C:
cd %DOCKER_TOOLBOX_INSTALL_PATH%
bash --login -i "%DOCKER_TOOLBOX_INSTALL_PATH%\start.sh" 'cd %UNIXDIR%; exec "${SHELL:-sh}"'
%OLD_CD%
cd %CURDIR%
