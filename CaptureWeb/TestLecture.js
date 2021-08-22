/*
var lineReader = require('line-reader')
lineReader.open('config.ini', function(err,reader) {
    if(err) console.log(err)
    while (reader.hasNextLine()) {
        reader.nextLine(function(err,line) {
            console.log(line);
        });
    }
    
  });
*/
const readline = require('readline');
const fs = require('fs');

const readInterface = readline.createInterface({
    input: fs.createReadStream('config.ini')
});

readInterface.on('line', function(line) {
    console.log(line);
});

readInterface.on('close', function() {
    console.log("fermeture du fichier")
});