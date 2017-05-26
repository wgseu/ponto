@echo OFF

java -jar SQLtoClass.jar -p %~dp0config.properties -t template -o %~dp0../tmp
if not %ERRORLEVEL% == 0 goto error
goto end

:error
goto end
:end
