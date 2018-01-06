@echo OFF
SET OLD_PATH=%PATH%
set PATH=%PATH%;%~pd0..\public\include\vendor\bin

cd ..
phpcbf --standard=psr2 public\include\api public\include\classes public\include\function public\include\library public\app public\categoria public\conta public\contato public\gerenciar public\produto public\sobre
cd %~pd0

SET PATH=%OLD_PATH%
