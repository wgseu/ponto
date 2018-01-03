@echo off

perl -0777 -i.original -pe "s/USE `GrandChef`\$\$\n//igs" ../database/model/script.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
perl -0777 -i.original -pe "s/USE `GrandChef`;\n//igs" ../database/model/script.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
perl -0777 -i.original -pe "s/END\$\$\n/END \$\$/igs" ../database/model/script.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
perl -0777 -i.original -pe "s/`GrandChef`\.//igs" ../database/model/script.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
rm -f ../database/model/script.sql.original
cp -f ../database/model/script.sql ../storage/db/dumps/script_no_trigger.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
perl -0777 -i.original -pe "s/\nDELIMITER \$\$.*?DELIMITER ;\n\n//igs" ../storage/db/dumps/script_no_trigger.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
perl -0777 -i.original -pe "s/' \/\* comment truncated \*\/ \/\*([^\*]+)\*\//$1'/igs" ../storage/db/dumps/script_no_trigger.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
perl -0777 -i.original -pe "s/([^\\\][\\\])([^\\\'])/$1\\\$2/igs" ../storage/db/dumps/script_no_trigger.sql
set ERRORCODE=%ERRORLEVEL%
if not %ERRORCODE% == 0 goto error
rm -f ../storage/db/dumps/script_no_trigger.sql.original

goto success
:error
goto finally
:success
:finally
exit /B %ERRORCODE%
