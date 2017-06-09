@echo OFF

java -jar SQLtoClass.jar -p %~dp0config.properties -t %~dp0template -o %~dp0tmp/generated
if not %ERRORLEVEL% == 0 goto error
goto end

:error
goto end
:end
