# Cacheur d'argument de commande

Programme permettant de cacher le dernier argument donné à une commande et de le remplacer par le contenu du fichier défini par la macro NOM_FICHIER

Appeler ce programme en lui donnant en argument la commande dont on veut cacher le dernier paramètre du htop, glances, ps, etc.
L'argument qui apparaitra dans ces listes sera celui donné lors de l'appel de la commande.
Le vrai argument qui sera pris en compte par le programme est écrit dans le fichier défini par la macro NOM_FICHIER dans le fichier injectpassword.c
De base le fichier se nomme ``urlVersCamera``

### Compilation
Pour compiler utilisez la commande:
```bash
gcc -O2 -fPIC -shared -o injectpassword.so injectpassword.c -ldl
```
### Utilisation
Pour utiliser:
```bash
LD_PRELOAD=$PWD/injectpassword.so Commande [args] ArgumentCaché
```
### Exemple
Exemple d'utilisation:
```bash
echo rtsp://admin:VerySecurePassword@192.168.1.191:554/
LD_PRELOAD=$PWD/injectpassword.so ffmpeg -rtsp_transport tcp -vcodec libx264 -acodec aac outputtest.mp4 -i ip 
```

# Script pour utilisation plus simple

La commande spawn() du module child_process ne permet pas lancer la commande présentée précédemment
Il y a donc un script ``cacherFFMPEG`` qui lui lance la commande.


### Utilisation

Exemple d'utilisation dans un shell:
```bash
echo rtsp://admin:VerySecurePassword@192.168.1.191:554/ > urlVersCamera
./cacherFFMPEG ffmpeg -y -c copy essaiexec.mp4 -i ip
```
Exemple d'utilisation dans node:
```javascript
NA.spawn = require('child_process').spawn;
 ffmpegProcess = NA.spawn("./cacherFFMPEG", ['ffmpeg','-rtsp_transport' ,'tcp' ,'-vcodec' ,'libx264', '-preset', 'ultrafast','-acodec', 'aac' ,'-y','fichier.mP4','-i' ,'ip'])
 ```