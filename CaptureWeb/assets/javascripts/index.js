// index.js - client

var content = document.getElementsByClassName("content")[0],
    button_gauche = document.getElementById("BoutonGauche"),
    button_droite = document.getElementById("BoutonDroite");
    button_stop = document.getElementById("BoutonStop");
    button_haut = document.getElementById("BoutonHaut");
    button_bas = document.getElementById("BoutonBas");
    button_zoom_in = document.getElementById("BoutonZoomIn");
    button_zoom_out = document.getElementById("BoutonZoomOut");

    button_gauche_hover = document.getElementById("BoutonGaucheHover"),
    button_droite_hover = document.getElementById("BoutonDroiteHover");
    button_haut_hover = document.getElementById("BoutonHautHover");
    button_bas_hover = document.getElementById("BoutonBasHover");
    button_zoom_in_hover = document.getElementById("BoutonZoomInHover");
    button_zoom_out_hover = document.getElementById("BoutonZoomOutHover");

    button_home = document.getElementById("BoutonHome");

    button_enregistrement = document.getElementById("BoutonEnregistrer");
    button_pause = document.getElementById("BoutonPause");
    button_stop_enregistrement = document.getElementById("BoutonStopEnregistrement");
    button_reprendre_enregistrement = document.getElementById("BoutonReprendre");

    button_camera1 = document.getElementById("BoutonCamera1");
    button_camera2 = document.getElementById("BoutonCamera2");

var currentCamera = 1;
var players = [];

players[currentCamera] = new JSMpeg.Player('ws://192.168.1.33:999'+currentCamera, {
  canvas: document.getElementById('canvas') // Canvas should be a canvas DOM element
})	

// On dit au serveur que le bouton a été appuyé
button_camera1.addEventListener("click", function () {
  players[currentCamera].stop() 
  currentCamera = 1;
  NA.socket.emit('changement_camera',currentCamera);
  players[currentCamera] = new JSMpeg.Player('ws://192.168.1.33:9991', {
    canvas: document.getElementById('canvas') // Canvas should be a canvas DOM element
  })
});

button_camera2.addEventListener("click", function () {

  players[currentCamera].stop() 
  currentCamera = 2;
  NA.socket.emit('changement_camera',currentCamera);

  players[currentCamera] = new JSMpeg.Player('ws://192.168.1.33:9992', {
    canvas: document.getElementById('canvas') // Canvas should be a canvas DOM element
  })	
});


button_gauche.addEventListener("click", function () {
    NA.socket.emit("bouton_interface_gauche");
});

button_droite.addEventListener("click", function () {
    NA.socket.emit("bouton_interface_droite");
});

button_haut.addEventListener("click", function () {
  NA.socket.emit("bouton_interface_haut");
});

button_bas.addEventListener("click", function () {
NA.socket.emit("bouton_interface_bas");
});

button_zoom_in.addEventListener("click", function () {
  NA.socket.emit("bouton_interface_zoom_in");
});

button_zoom_out.addEventListener("click", function () {
NA.socket.emit("bouton_interface_zoom_out");
});

button_stop.addEventListener("click", function () {
  NA.socket.emit("bouton_interface_stop");
});


button_gauche_hover.addEventListener("mouseleave", function () {
  NA.socket.emit("bouton_interface_stop");
});

button_gauche_hover.addEventListener("mouseenter", function () {
  NA.socket.emit("bouton_interface_gauche");
});

button_droite_hover.addEventListener("mouseleave", function () {
  NA.socket.emit("bouton_interface_stop");
});

button_droite_hover.addEventListener("mouseenter", function () {
  NA.socket.emit("bouton_interface_droite");
});

button_haut_hover.addEventListener("mouseleave", function () {
  NA.socket.emit("bouton_interface_stop");
});

button_haut_hover.addEventListener("mouseenter", function () {
  NA.socket.emit("bouton_interface_haut");
});

button_bas_hover.addEventListener("mouseleave", function () {
  NA.socket.emit("bouton_interface_stop");
});

button_bas_hover.addEventListener("mouseenter", function () {
  NA.socket.emit("bouton_interface_bas");
});

button_zoom_out_hover.addEventListener("mouseleave", function () {
  NA.socket.emit("bouton_interface_stop");
});

button_zoom_out_hover.addEventListener("mouseenter", function () {
  NA.socket.emit("bouton_interface_zoom_out");
});

button_zoom_in_hover.addEventListener("mouseleave", function () {
  NA.socket.emit("bouton_interface_stop");
});

button_zoom_in_hover.addEventListener("mouseenter", function () {
  NA.socket.emit("bouton_interface_zoom_in");
});


button_home.addEventListener("click", function () {
  
  NA.socket.emit("bouton_interface_home");
  
});



button_enregistrement.addEventListener("click", function () {
  NA.socket.emit("bouton_interface_enregistrement");
});

button_stop_enregistrement.addEventListener("click", function () {
  NA.socket.emit("bouton_interface_stop_enregistrement");
});

button_pause.addEventListener("click", function () {
  NA.socket.emit("bouton_interface_pause");
});

button_reprendre_enregistrement.addEventListener("click", function () {
  NA.socket.emit("bouton_interface_reprendre");
});