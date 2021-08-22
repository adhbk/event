# Driver de communication camera HIKVISION
Ce programme permet de commander une ou plusieurs caméras HIKVISION en ligne de commande.

## Compilation
Un Makefile est mis à disposition pour la compilation du programme si vous souhaitez le modifier. 
Il suffit de se mettre dans le répertoire du projet et exécuter la commande `make`

Essayez de lancer le fichier exécutable ``./commandeCamera``, un message Erreur d'arguments devrait apparaitre.

L'architecture des librairies doit rester la même par rapport au fichier exécutable ou alors changer le Makefile accordément.
## Fonctionnalités
Les fonctionnalités disponibles sont:
- Le contrôle PTZ de la caméra:
    - PAN (Gauche / Droite)
    - TILT (Haut / Bas)
    - ZOOM (IN / OUT)
- L'utilisation de la voix de dieu
- La vérification du Numéro de série de la caméra

## Utilisation
Il y a un seul fichier exécutable ``commandeCamera``, les paramètres donnés lors de l'appel définissent le comportement.
#### Fichier de configuration
Les données nécessaires à la communication sont à rentrer dans un fichier ``config.ini``
Le fichier doit être situé au même niveau que le programme qui exécute la commande.
```bash
cd ~/
./mon_dossier/commandeCamera
Ne fonctionnera pas si le fichier config.ini est dans ~/mon_dossier 
```
Un fichier exemple est donné à la racine du projet.
Les données minimum au fonctionnement du programme sont les suivantes:
```
[Cam1]
PortCam = 8000
IpCam = 192.168.1.191
IdentifiantCam = "admin"
PasswordCam = "mireille"
LicCam= "946e1b68459137fcc3b4180264c0c96e1dcc2056acddc59db681e286f22da82d0c49ec78d1ff57e888d496acc0f55d5f45dab6fbfc1"
```
#### Appel de la commande
La structure de la commande est la suivante:
```bash
./commandeCamera NUMERO_CAMERA COMMAND TARGET OPTION
COMMAND = [ START  , STOP  ,  CHECK]
TARGET =  [ ZOOM   ,    TILT   ,      PAN      , HOME  ,  VOICE ]
OPTION = [[IN,OUT] , [UP,DOWN] , [LEFT,RIGHT]]

Exemples: 
./commandeCamera 1 START ZOOM IN
./commandeCamera 1 STOP
./commandeCamera 1 START HOME
```
La commande ``STOP`` s'applique à tous les mouvements de caméra.
Pour arrêter la voix il faut cependant bien préciser ``STOP VOICE``.

#### Valeurs de retour
Retourne :
 *   0 en cas de succès
 *   1 en cas d'erreur d'arguments
 *   2 en cas de fichier config.ini manquant
 *   3 en cas de fichier config.ini mal écrit
 *   4 en cas d'erreur de connexion à la camera
 *   5 en cas de mauvaise licence camera

 #### Voix de dieu
 Lors de l'appel de la commande pour activer la voix de dieu, le processus reste ouvert.
 La connexion est initialisée avec la caméra et reste active tant que le processus est ouvert OU que la commande ``STOP VOICE`` n'a pas été appelée.
 Autrement dit, pour arrêter la connexion il faut soit tuer le processus soit appeler ``STOP VOICE``.
 Attention l'appel à la commande ``STOP VOICE`` ne tue pas le processus ``START VOICE``, il restera ouvert en ne faisant rien.
 Le son transmis à la caméra est le son entrant dans le micro par défaut de la machine.

 #### Vérification de licence
La commande ``CHECK`` permet de vérifier la licence donnée dans le fichier ``config.ini`` .
La commande renvoie 0 en cas de licence correspondante et 5 en cas d'erreur.

 #### Choses à savoir
Une connexion est effectuée à chaque commande, il est donc possible d'appeler plusieurs fois le programme simultanément. 
Le comportement peut cependant ne pas être exactement celui attendu, le dernier qui a envoyé la commande aura raison.
Si trop de commandes arrivent trop vite, la caméra peut bloquer toute nouvelle connexion entrante et il faut la redémarrer.
Le dossier lib doit être dans le même répertoire que le répertoire d'appel de la commande.

##### Auteur

Driver réalisé par:
Adrien Prévost - adrien.prev28@gmail.com