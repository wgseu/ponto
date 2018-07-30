@echo OFF 

set sevenzip="%programfiles%\7-zip\7z.exe"
if not exist %sevenzip% (
	set sevenzip="%programfiles(x86)%\7-zip\7z.exe"
)
if not exist %sevenzip% goto no_7z

set version="1.9.4.5"
if not "%~1" == "" set version="%~1"

gpd.py "%CD%"

%sevenzip% a -y -pSenhaDoChurrascaria "%CD%\Produtos e Categorias %version%.chb" "%CD%\MySQLBackup.sql" > nul
REM del /F/Q MySQLBackup.sql

goto end

:no_7z
echo O compactador de arquivos 7-zip nao esta instalado
pause
goto end

:end