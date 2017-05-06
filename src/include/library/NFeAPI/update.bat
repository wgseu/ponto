@echo off

rm -rf .git
git init
git remote add origin https://github.com/mazinsw/nfe-api.git
git fetch
git checkout -f -t origin/master
cmd /C composer install --no-dev --optimize-autoloader
git filter-branch --prune-empty --subdirectory-filter api HEAD
md api
move /Y NFe api\
move /Y util api\
rm -rf composer.lock
rm -rf .git
