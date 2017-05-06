@echo OFF

cd ..\..\sql\model
call fix_script
cd %~dp0
java -jar SQLtoClass.jar -p %~dp0config.properties -t template -o %~dp0../../tmp
if not %ERRORLEVEL% == 0 pause > NUL
java -jar SQLtoClass.jar -p %~dp0delphi.properties
if not %ERRORLEVEL% == 0 pause > NUL
java -jar SQLtoClass.jar -p %~dp0config.properties
if not %ERRORLEVEL% == 0 pause > NUL
for %%f in (%~dp0..\..\tmp\include\dao\*.class.php) do (
	perl -0777 -i.original -pe "s/\<\?php\r?\n\r?\n/\<\?php\n\/\*\n	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA\n	Este arquivo \xC3\xA9 parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes\.\n	O GrandChef \xC3\xA9 um software propriet\xC3\xA1rio; voc\xC3\xAA n\xC3\xA3o pode redistribu\xC3\xAD-lo e\/ou modific\xC3\xA1-lo\.\n	DISPOSI\xC3\x87\xC3\x95ES GERAIS\n	O cliente n\xC3\xA3o dever\xC3\xA1 remover qualquer identifica\xC3\xA7\xC3\xA3o do produto, avisos de direitos autorais,\n	ou outros avisos ou restri\xC3\xA7\xC3\xB5es de propriedade do GrandChef\.\n\n	O cliente n\xC3\xA3o dever\xC3\xA1 causar ou permitir a engenharia reversa, desmontagem,\n	ou descompila\xC3\xA7\xC3\xA3o do GrandChef\.\n\n	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA\n\n	GrandChef \xC3\xA9 a especialidade do desenvolvedor e seus\n	licenciadores e \xC3\xA9 protegido por direitos autorais, segredos comerciais e outros direitos\n	de leis de propriedade\.\n\n	O Cliente adquire apenas o direito de usar o software e n\xC3\xA3o adquire qualquer outros\n	direitos, expressos ou impl\xC3\xADcitos no GrandChef diferentes dos especificados nesta Licen\xC3\xA7a\.\n\*\/\n/igs" "%~dp0..\..\tmp\include\dao\%%~nf.php"
)
del /F /Q %~dp0..\..\tmp\include\dao\*.class.php.original
copy /Y "%~dp0..\..\tmp\delphi\classes\*.pas" "%~dp0..\grandchef\classes\"