// common.js - controlleur

    /* Partie du programme qui s'exécute au démarrage */
exports.setModules = function () {
var   NA = this;
      NA.sys = require('sys')
      NA.exec = require('child_process').exec;
      NA.spawn = require('child_process').spawn;
      NA.promises = require('fs').promises; // or require('fs/promises') in v10.0.0
      NA.cookie = require("cookie");
      NA.ffmpeg = require('fluent-ffmpeg');

  NA.stream = require('node-rtsp-stream')

  var lineReader = require('line-reader')

  const readline = require('readline');
  const fs = require('fs');


  // Informations sur les caméras NA.IP[2] => IP de la caméra 2
  NA.IP = []
  NA.Port = []
  NA.Login = []
  NA.Mdp = []
  NA.PTZ = []
  NA.Licence = []
  let currentCamera = -1;

  const readInterface = readline.createInterface({
      input: fs.createReadStream('config.ini')
  });

  readInterface.on('line', function(line) {
    if(line.includes("[Cam")){
      console.log("camera n°" + line.substr(4, (line.length-1) - 4  ))
      currentCamera = parseInt(line.substr( 4, (line.length-1) - 4 ))
    }
    if(currentCamera != -1){
      //Parse Camera info
      if(line.includes("PortCam")){
        NA.Port[currentCamera]  = line.substr(10, (line.length) - 10)
      }

      if(line.includes("IpCam")){
        NA.IP[currentCamera]    = line.substr(8,  (line.length) - 8 )
      }

      if(line.includes("IdentifiantCam")){
        NA.Login[currentCamera] = line.substr(18, (line.length-1) - 18)
      }

      if(line.includes("PasswordCam")){
        NA.Mdp[currentCamera]  = line.substr(15, (line.length-1) - 15)
      }

      if(line.includes("PTZ")){
        NA.PTZ[currentCamera]  = parseInt(line.substr(6, (line.length) - 6))
      }

      if(line.includes("LicCam")){
        NA.Licence[currentCamera]  = line.substr(9, (line.length-1) - 9)
      }
    }
    
    console.log(line);
  });

  readInterface.on('close', function() {
    console.log(NA.IP)
    console.log(NA.Port)
    console.log(NA.Login)
    console.log(NA.Mdp)
    console.log(NA.PTZ)
    console.log(NA.IP.length)

    for(let i = 1 ; i < NA.IP.length ; ++i){
        //var rtspURL; //rtsp://admin:event+team@192.168.1.191:554/
        console.log("on est là")
        let rtspURL = `rtsp://${NA.Login[i]}:${NA.Mdp[i]}@${NA.IP[i]}/`
        console.log(rtspURL)
        let finished = false;
        NA.promises.writeFile("urlVersCamera"+i, rtspURL)
        .then(() => {
          stream = new NA.stream({
            name: 'name',
            //streamUrl: 'rtsp://admin:event+team@192.168.1.191:554/',
            streamUrl: ''+i,
            wsPort: 9990+i,
            ffmpegPath: "./cacherFFMPEG",
            ffmpegOptions: { // options ffmpeg flags
              '-stats': '', // an option with no neccessary value uses a blank string
              '-r': 30, // options with required values specify the value after the key
                '-tune': 'zerolatency',
                '-preset': 'ultrafast'
            }
            });
            finished = true;
            console.log("ici")
        });
        console.log("là")
        //while(!finished);
        console.log("Probablement pas là")
    }
  });

  

/*
  lineReader.open('config.ini', function(err,reader) {
    if(err) console.log(err)
    while (reader.hasNextLine()) {
        reader.nextLine(function(err,line) {
          console.log(currentCamera)
          
            if(line.includes("[Cam")){
              console.log("camera n°" + line.substr(4, (line.length-1) - 4  ))
              currentCamera = parseInt(line.substr( 4, (line.length-1) - 4 ))
            }
            if(currentCamera != -1){
              //Parse Camera info
              if(line.includes("PortCam")){
                NA.Port[currentCamera]  = line.substr(10, (line.length) - 10)
              }

              if(line.includes("IpCam")){
                NA.IP[currentCamera]    = line.substr(8,  (line.length) - 8 )
              }

              if(line.includes("IdentifiantCam")){
                NA.Login[currentCamera] = line.substr(18, (line.length-1) - 18)
              }

              if(line.includes("PasswordCam")){
                NA.Mdp[currentCamera]  = line.substr(15, (line.length-1) - 15)
              }

              if(line.includes("PTZ")){
                NA.PTZ[currentCamera]  = parseInt(line.substr(6, (line.length) - 6))
              }

              if(line.includes("LicCam")){
                NA.Licence[currentCamera]  = line.substr(9, (line.length-1) - 9)
              }
            }
            
            console.log(line);
            console.log("a")
        });
    }
    console.log(NA.IP)
    console.log(NA.Port)
    console.log(NA.Login)
    console.log(NA.Mdp)
    console.log(NA.PTZ)
    console.log(NA.IP.length)

    for(let i = 1 ; i < NA.IP.length ; ++i){
        //var rtspURL; //rtsp://admin:event+team@192.168.1.191:554/
        console.log("on est là wesh")
        let rtspURL = `rtsp://${NA.Login[i]}:${NA.Mdp[i]}@${NA.IP[i]}/`
        console.log(rtspURL)
        let finished = false;
        NA.promises.writeFile("urlVersCamera", rtspURL)
        .then(() => {
          stream = new NA.stream({
            name: 'name',
            //streamUrl: 'rtsp://admin:event+team@192.168.1.191:554/',
            streamUrl: 'Michel',
            wsPort: 9990+i,
            ffmpegPath: "./cacherFFMPEG",
            ffmpegOptions: { // options ffmpeg flags
              '-stats': '', // an option with no neccessary value uses a blank string
              '-r': 30, // options with required values specify the value after the key
                '-tune': 'zerolatency',
                '-preset': 'ultrafast'
            }
            });
            finished = true;
    
        });

        while(!finished);

    }

    
  });
*/
};

exports.setSessions = function (next) {
  var NA = this;

  NA.storedSessionId = null;

  next();
};