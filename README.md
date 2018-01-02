# GrandChef
## Instalação
**Windows**
- Instalar Git pelo site https://git-scm.com/download/win
- Instalar o mingw32-base pelo mingw-get-setup https://sourceforge.net/projects/mingw/
- Adicionar a pasta bin do MinGW no PATH
- Copiar arquivo "mingw32-make.exe" da pasta bin do MinGW para "make.exe"
- Instalar Sublime Text 3
**Windows 7**
- Instalar Docker Toolbox
- Caso o projeto esteja em outra unidade que a unidade ```C:```
Mudar a linha ```60``` do arquivo ```"%ProgramFiles%\Docker Toolbox\start.sh"``` de:
```"${DOCKER_MACHINE}" create -d virtualbox $PROXY_ENV "${VM}"```
Para:
```"${DOCKER_MACHINE}" create -d virtualbox --virtualbox-share-folder "D:\Projects:/d/Projects" $PROXY_ENV "${VM}"```

## Execução
