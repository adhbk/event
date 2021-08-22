// index.js - controlleur

// On référence les actions de réponse et d'envoi globaux côté serveur.
// Ce code sera exécuté pour toute entrée WebSocket entrante.

/**
 * Recoit les messages websockets du client.
 * 
 * Connecte les boutons de l'interface avec leur fonction
 */
exports.setSockets = function () {
    var NA = this,
        path = NA.modules.path;
        io = NA.io;

    
    //////////// CONSTANTES ////////////////////
    NA.temporaryFilesPath = ""; //Chemin vers le dossier des vidéos temporaires 
    NA.outputVideosPath = "EnregistrementComplets/"; //Chemin vers le dossier des enregistrements finaux 
    NA.outputVideoExtension = '.mp4';//Extension des vidéos d'enregistrement
    NA.cameraDriverPath = "../c++-control-camera-voix-dieu-V40/commandeCamera";//Chemin vers l'exécutable driver de la camera
    NA.cameraIP = "192.168.1.191"
    NA.cameraPassword = "event+team"
    NA.cameraLogin = "admin"
    NA.cameraRTSPPort = 554


    //Variables 
    var ffmpegProcess; //Processus ffmpeg qui transcode le flux en vidéo
    var videoCpt = 1; //Compteur de vidéos à concaténer <=> nombre de pauses + 1
    var outputVideoName; //Nom de la vidéo de sortie suivant la convention de nommage
    var cameraActuelle = 1;

    /* Booléens pour l'action des boutons Enregistrement / pause etc */
    var isRecording = false; 
    var isPaused = false;


    console.log("exports.setSockets");


    // Attendre un lien valide entre client et serveur
    io.sockets.on("connection", function (socket) {
      console.log("nouvel onglet")


      var cookie_string = socket.request.headers.cookie;
      var parsed_cookies = NA.cookie.parse(cookie_string);
      var connect_sid = parsed_cookies['user.sid'];
      console.log(connect_sid)


      


      // On a appuyé sur le bouton de l'interface graphique
      socket.on("changement_camera", function (numero) {
        cameraActuelle = numero;
      });


      ////////////////////////// Boutons de Commande PTZ ////////////////////////////////////////
        // On a appuyé sur le bouton de l'interface graphique
        socket.on("bouton_interface_gauche", function () {
          console.log("gauche\n");
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " START PAN LEFT", function(err, stdout, stderr) {

            console.log(stdout);
          });
          
          dir.on('exit', function (code) {
            // exit code is code
          });
        });

        socket.on("bouton_interface_droite", function () {
          console.log("droite\n");
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " START PAN RIGHT", function(err, stdout, stderr) {

            console.log(stdout + Date.now() + "");
          });
          
          dir.on('exit', function (code) {
            // exit code is code
          });
        });

        socket.on("bouton_interface_stop", function () {
          console.log("stop\n");
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " STOP", function(err, stdout, stderr) {

            console.log(stdout);
          });
          
          dir.on('exit', function (code) {
            // exit code is code
          });
        });

        socket.on("bouton_interface_haut", function () {
          console.log("haut\n");
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " START TILT UP", function(err, stdout, stderr) {

            console.log(stdout);
          });
          
          dir.on('exit', function (code) {
            // exit code is code
          });
        });

        socket.on("bouton_interface_bas", function () {
          console.log("bas\n");
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " START TILT DOWN", function(err, stdout, stderr) {

            console.log(stdout);
          });
          
          dir.on('exit', function (code) {
            // exit code is code
          });
        });

        socket.on("bouton_interface_zoom_in", function () {
          console.log("zoom in\n");
          console.log("./" + NA.cameraDriverPath + " " + cameraActuelle + " START ZOOM IN")
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " START ZOOM IN", function(err, stdout, stderr) {

            console.log(stdout);
          });
          dir.on('exit', function (code) {
            // exit code is code
          });
        });

        socket.on("bouton_interface_zoom_out", function () {
          console.log("zoom out\n");
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " START ZOOM OUT", function(err, stdout, stderr) {

            console.log(stdout);
          });
          
          dir.on('exit', function (code) {
            // exit code is code
          });
        });

        socket.on("bouton_interface_home", function () {
          console.log("zoom out\n");
          dir = NA.exec("./" + NA.cameraDriverPath + " " + cameraActuelle + " START HOME", function(err, stdout, stderr) {
            if (err) {
              // should have err.code here?
              console.log("une erreur incroyable \n");
              console.log(err);
            }
            console.log(stdout);
          });
          
          dir.on('exit', function (code) {
            // exit code is code
            console.log("une erreur magnifique \n");
            console.log(code)
          });
        });

      ////////////////////////// Boutons d'enregistrement ////////////////////////////////////////

        /** Bouton 'Enregistrer'
         * 
         * Si l'on est pas déjà en train d'enregistrer ni en pause
         * Alors
         *    On lance l'enregistrement
         *    Définir l'enregistrement comme étant lancé 
         */
        socket.on("bouton_interface_enregistrement", function () {

          console.log("appui bouton interface enregistrement")
          if(!isRecording && !isPaused){
            outputVideoName = getOutputVideoName(cameraActuelle);
            ffmpegProcess = startRecording(1,videoCpt,outputVideoName,cameraActuelle,NA);
            isRecording = true;
          }

        });

        /** Bouton 'Stop enregistrement'
         * 
         * Si l'on est en train d'enregistrer
         * Alors
         *    On tue le processus FFMPEG qui enregistre la vidéo
         *    Après la fin du processus on concatène les vidéos
         *    Se remettre dans l'état de base 
         */
        socket.on("bouton_interface_stop_enregistrement", function () {
          console.log("appui bouton interface stop enregistrement");
          if(isRecording){

            ffmpegProcess.on('exit', function (code) {
              // exit code is code
              concatenerVideos(videoCpt,outputVideoName,NA);
              videoCpt = 1;
              isRecording = false;
              isPaused = false;
            });

            console.log("RIP FFMPEG");
            killFFMPEG(ffmpegProcess.pid);
          }
        });

        /** Bouton 'Pause'
         * 
         * Si l'on est en train d'enregistrer et pas déjà en pause
         * Alors
         *    On tue le processus FFMPEG qui enregistre la vidéo
         *    On se met dans l'état Pause
         *    On incrémente le compteur de vidéos à concaténer
         */
        socket.on("bouton_interface_pause", function () {
          console.log("appui bouton interface pause");

          if(isRecording && (!isPaused)){
            killFFMPEG(ffmpegProcess.pid);
            isRecording = false;
            isPaused = true;

            ++videoCpt;
          }
          
        });

        /** Bouton 'Reprendre'
         * 
         * Si l'enregistrement n'est pas en cours et que l'on est en pause
         * Alors
         *    On redémarre l'enregistrement
         */
        socket.on("bouton_interface_reprendre", function () {
          console.log("appui bouton interface reprendre");

          if(!isRecording && isPaused){
            ffmpegProcess = startRecording(1,videoCpt,outputVideoName,cameraActuelle,NA);
            isRecording = true;
            isPaused = false;
          }
        });

    });

/** Tue le processus FFMPEG dont le PID est donné en paramètre
 *  (Marche avec d'autres processus que FFMPEG mais faut pas le dire)
 * 
 * @param {int} pid PID du processus FFMPEG à tuer 
 * @returns La commande qui tue ffmpeg
 */
 function killFFMPEG(pid){
  console.log("on tue ffmpeg, PID = " + pid);
 
  dir = NA.exec("pkill --signal 2 -P " + pid, function(err, stdout, stderr) {
    
  if (err) {
      // should have err.code here?
    }
    console.log(stdout);
  });
  return dir;
}

/**
 * Lance l'enregistrement du flux de la caméra numéro cameraNum
 * 
 * @param {int} cameraNum Numéro de la caméra à enregistrer dans le fichier config.ini
 * @param {int} videoNum Numéro de la vidéo qui compose l'enregistrement total (= nombre de pauses + 1)
 * @param {string} outputVideoName Nom de la vidéo ainsi créée
 * @returns Le processus ffmpeg créé
 */
function startRecording(cameraNum,videoNum,outputVideoName,cameraActuelle){
  console.log("Lancement enregistrement");
  var rtspURL; //rtsp://admin:event+team@192.168.1.191:554/
  var ffmpegProcess;
  //rtspURL = `rtsp://${NA.Login[cameraActuelle]}:${NA.Mdp[cameraActuelle]}@${NA.IP[cameraActuelle]}/`

 // NA.promises.writeFile("urlVersCamera", rtspURL)
    //.then(() => {

      ffmpegProcess = NA.spawn("./cacherFFMPEG", ['ffmpeg','-rtsp_transport' ,'tcp' ,'-vcodec' ,'libx264', '-preset', 'ultrafast','-acodec', 'aac' ,'-y',NA.temporaryFilesPath + outputVideoName + videoNum + NA.outputVideoExtension,'-i' ,''+cameraActuelle])
      ffmpegProcess.stdout.on('data', (data) => {
        console.log(`stdout: ${data}`);
      });
      
      ffmpegProcess.stderr.on('data', (data) => {
        console.error(`stderr: ${data}`);
      });
      
      ffmpegProcess.on('close', (code) => {
        console.log(`child process exited with code ${code}`);
      });


   // });



  return ffmpegProcess;
}

/**
 * Concatène les morceaux de vidéos créés par les pauses
 * 
 * La vidéo de sortie est dans le dossier NA.outputVideosPath
 * Les noms des vidéos à concaténer sont outputVideoName + videoNum + extension
 * 
 * @param {int} videoNb Le nombre de vidéos à concaténer
 * @param {string} outputVideoName Nom de la vidéo concaténée
 */
function concatenerVideos(videoNb,outputVideoName){
  console.log("Ici concaténation des vidéos");
  console.log("concaténation de " + videoNb + " vidéos")
  
  if(videoNb === 1){
    console.log("Copie du fichier temporaire")
    var copie = NA.spawn("cp", [ NA.temporaryFilesPath + outputVideoName + '1' + NA.outputVideoExtension , NA.outputVideosPath + outputVideoName + NA.outputVideoExtension])
    copie.on('close', (code) => {
        console.log("Suppression des fichiers temporaires")
        NA.exec("rm -v " + NA.temporaryFilesPath + outputVideoName + '?*' +NA.outputVideoExtension)
    });
    return;
  }

  const fs = require('fs')

  var videoList = "";

  for(var i =1 ; i <= videoNb ; ++i){
    videoList += "file " + NA.temporaryFilesPath + outputVideoName + i + NA.outputVideoExtension + "\n";
  }
  
  
  NA.promises.writeFile("fichiersAConcatener.txt", videoList)
    .then(() => {
      concatProcess = NA.spawn("ffmpeg", ['-y' ,'-f','concat',"-safe" ,'0' ,'-i','fichiersAConcatener.txt','-c','copy',NA.outputVideosPath + outputVideoName + NA.outputVideoExtension]);
      concatProcess.stdout.on('data', (data) => {
        console.log(`stdout: ${data}`);
      });
      
      concatProcess.stderr.on('data', (data) => {
        console.error(`stderr: ${data}`);
      });
      
      concatProcess.on('exit', (code) => {
        console.log(`child process exited with code ${code}`);
        
        console.log('concatenation terminée, suppression des fichiers temporaires')

        NA.exec("rm -v " + NA.temporaryFilesPath + outputVideoName + '?*' +NA.outputVideoExtension)
        
        return;
      });

      
  })



}
/**
 * Retourne le nom du fichier à créer
 * 
 * Contient la date et le numéro de la salle
 * Pas les informations sur le praticien ni patient
 * 
 * @param {int} numSalle Numéro identifiant la salle enregistrée
 * @returns Le nom du fichier 
 */
function getOutputVideoName(numSalle){
/*
Nom du praticien
_1_
Prénom du praticien
_2_
Identifiant AD du praticien
_3_
Nom du patient
_4_
Prénom du patient
_5_
IPP du patient
_6_
IEP
_7_
date d’enregistrement
_8_
heure d’enregistrement
_9_
identifiant salle
_10_
.mp4
*/
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0');
var hours = String(today.getHours());
var minutes = String(today.getMinutes());
var seconds = String(today.getSeconds());
var yyyy = today.getFullYear();

today = yyyy + mm + dd;

  let outputVideoName = "" +
  '_1_'+
  ""+
  '_2_'+
  ""+
  '_3_'+
  ""+
  '_4_'+
  ""+
  '_5_'+
  ""+
  '_6_'+
  ""+
  '_7_'+
  today +
  '_8_'+
  (hours.toString().length == 1   ? '0' + hours   : hours)   +
  
  (minutes.toString().length == 1 ? '0' + minutes : minutes) +
  
  (seconds.toString().length == 1 ? '0' + seconds : seconds) +
  '_9_'+
  numSalle+
  '_10_'

  return outputVideoName;
}
};


