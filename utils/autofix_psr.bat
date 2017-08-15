@echo OFF
set PATH=%PATH%;%~pd0..\src\include\vendor\bin

cd ..
phpcbf --standard=psr2 src\include\api src\include\classes src\include\function src\include\library src\app src\categoria src\conta src\contato src\gerenciar src\produto src\sobre
cd %~pd0
