/*var d = new Date()
console.log([d.getDay(),d.getMonth(),d.getFullYear()]);
*/
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = dd + '/' + mm + '/' + yyyy;
console.log(today);
/* var exec = require('child_process').exec;
 var execFile = require('child_process').execFile;

 var spawn = require('child_process').spawn;

var process = spawn("./cacherFFMPEG", ['ffmpeg','-y' ,'-c','copy', 'AHbon.mp4','-i','MICHEL']);

process.stdout.on('data', (data) => {
    console.log(`stdout: ${data}`);
});
*/

//console.log(parseInt("[Cam2]"))
/*
var process = exec("LD_PRELOAD=$PWD/../hideFFMPEG/injectpassword.so ffmpeg -y -c copy essaiexec.mp4 -i MICHEL" , function(err, stdout, stderr) {

    if (err) {
    // should have err.code here?
    }
    console.log(stdout);
});

process.on('exit', function (code) {
    // exit code is code
    console.log("c'est fini avec " + code)
});

process.on('stdout', function (code) {
    // exit code is code
    console.log("stdout: " + code)
});

process.stdout.on('data', function(data) {
    console.log(data); 
});
process.on('data', function(data) {
    console.log(data); 
});
*/