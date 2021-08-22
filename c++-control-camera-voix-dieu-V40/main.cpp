/** Auteur: Adrien Prevost 
 *  adrien.prev28@gmail.com
 *  11/09/2021
 *  
 *  Driver de communication avec camera HIKVISION
 *  
 *  Permet de contrôler une camera en PTZ
 * 
 *  Retourne:
 *   0 en cas de succès
 *   1 en cas d'erreur d'arguments
 *   2 en cas de fichier config.ini manquant
 *   3 en cas de fichier config.ini mal écrit
 *   4 en cas d'erreur de connexion à la camera
 *   5 en cas de mauvaise licence camera
 */


#include "incEn/HCNetSDK.h"
#include "stdio.h"
#include "string.h"
#include <iostream>
#include <unistd.h>
#include <string>
#include <cstring>
#include <fstream>


#define CONFIG_FILE_PATH "config.ini"
#define NUM_ARG_NUM_CAM 1
#define NUM_ARG_COMMAND 2
#define NUM_ARG_TARGET 3
#define NUM_ARG_OPTION 4

#define SUCCESS 0
#define ERROR_ARGUMENTS 1
#define ERROR_CONFIG_FILE_NOT_FOUND 2
#define ERROR_IN_CONFIG_FILE 3
#define ERROR_CONNEXION 4
#define ERROR_LICENCE 5


/*
#define ZOOM_IN            11    // Zoom in
#define ZOOM_OUT        12    // Zoom out 
#define FOCUS_NEAR      13  // Focus in 
#define FOCUS_FAR       14  // Focus out
#define IRIS_OPEN       15  // Iris open 
#define IRIS_CLOSE      16  // Iris close 

#define TILT_UP         21    // PTZ tilt up 
#define TILT_DOWN       22    // PTZ tilt down
#define PAN_LEFT        23    // PTZ pan left 
#define PAN_RIGHT       24    // PTZ pan right
#define UP_LEFT         25    // PTZ turn up and left 
#define UP_RIGHT        26    // PTZ turn up and right 
#define DOWN_LEFT       27    // PTZ turn down and left 
#define DOWN_RIGHT      28    // PTZ turn down and right 
#define PAN_AUTO        29    // PTZ auto pan 
*/

int printErrorAndExit(int errorCode);
LONG connectionCamera();
int lireFichierIni(char* argv[]);
int verifierLicence();
void callbackVoixDieu(LONG lVoiceComHandle, char *pRecvDataBuffer, DWORD dwBufSize, BYTE byAudioFlag, DWORD dwUser);
using namespace std;

string ip;
string login;
string mdp;
string licCam;
WORD port;
LONG voiceComHandle;
NET_DVR_USER_LOGIN_INFO loginInfo;
NET_DVR_DEVICEINFO_V30 struDeviceInfo;
NET_DVR_DEVICEINFO_V40 struDeviceInfov40;

/**./executable NUMERO_CAMERA COMMAND TARGET OPTION
 * COMMAND = [ START  , STOP ]
 * TARGET =  [ ZOOM   ,    TILT   ,      PAN      , HOME  ,  VOICE ]
 * OPTION = [[IN,OUT] , [UP,DOWN] , [LEFT,RIGHT]]
 * 
 * Exemples: 
 * ./executable 1 START ZOOM IN
 */
int main(int argc, char* argv[]){
    if(argc < 3 || argc > 5){
        return printErrorAndExit(ERROR_ARGUMENTS);
    }

    LONG userId;
    NET_DVR_PREVIEWINFO PreviewInfo;
    LONG lRealPlayHandle;



    if(lireFichierIni(argv) != SUCCESS)
        return printErrorAndExit(ERROR_CONFIG_FILE_NOT_FOUND);


    if(!strcmp (argv[NUM_ARG_COMMAND],"START")){
        //COMMAND START
        if(!strcmp (argv[NUM_ARG_TARGET],"ZOOM")){
            
        ///////////// ZOOM ////////////////////////////////
            if(!strcmp (argv[NUM_ARG_OPTION],"IN")){
                std::cout << "COMMANDE ZOOM IN" << std::endl;

                userId = connectionCamera();
                std::cout << "userId= " << userId << std::endl;
                if(userId == -1)
                    return printErrorAndExit(ERROR_CONNEXION);
                NET_DVR_PTZControlWithSpeed_Other(userId, 1, ZOOM_IN, 0, 2);
                
                return SUCCESS;
                    
            }
        ///////////// DEZOOM ////////////////////////////////
            if(!strcmp (argv[NUM_ARG_OPTION],"OUT")){
                std::cout << "COMMANDE ZOOM OUT" << std::endl;

                userId = connectionCamera();
                std::cout << "userId= " << userId << std::endl;
                if(userId == -1)
                    return printErrorAndExit(ERROR_CONNEXION);
                NET_DVR_PTZControlWithSpeed_Other(userId, 1, ZOOM_OUT, 0, 2);
                
                return SUCCESS;
                     
            }
            return printErrorAndExit(ERROR_ARGUMENTS);
        }

        if(!strcmp (argv[NUM_ARG_TARGET],"TILT")){
        ///////////// Tourne vers le haut ////////////////////////////////
            if(!strcmp (argv[NUM_ARG_OPTION],"UP")){
                std::cout << "COMMANDE TILT UP" << std::endl;

                userId = connectionCamera();
                std::cout << "userId= " << userId << std::endl;
                if(userId == -1)
                    return printErrorAndExit(ERROR_CONNEXION);
                NET_DVR_PTZControlWithSpeed_Other(userId, 1, TILT_UP, 0, 2);
                
                return SUCCESS;
                     
            }
        ///////////// Tourne vers le base ////////////////////////////////
            if(!strcmp (argv[NUM_ARG_OPTION],"DOWN")){
                std::cout << "COMMANDE TILT DOWN" << std::endl;

                userId = connectionCamera();
                std::cout << "userId= " << userId << std::endl;
                if(userId == -1)
                    return printErrorAndExit(ERROR_CONNEXION);
                NET_DVR_PTZControlWithSpeed_Other(userId, 1, TILT_DOWN, 0, 2);
                
                return SUCCESS;
                     
            }
            return printErrorAndExit(ERROR_ARGUMENTS);
        }

        if(!strcmp (argv[NUM_ARG_TARGET],"PAN")){
        ///////////// Tourne à gauche ////////////////////////////////
            if(!strcmp (argv[NUM_ARG_OPTION],"LEFT")){
                std::cout << "COMMANDE PAN LEFT" << std::endl;
                userId = connectionCamera();
                std::cout << "userId= " << userId << std::endl;
                if(userId == -1)
                    return printErrorAndExit(ERROR_CONNEXION);
                NET_DVR_PTZControlWithSpeed_Other(userId, 1, PAN_LEFT, 0, 2);
                
                return SUCCESS;
                     
            }
        ///////////// Tourne à droite ////////////////////////////////
            if(!strcmp (argv[NUM_ARG_OPTION],"RIGHT")){

                std::cout << "COMMANDE PAN RIGHT" << std::endl;
                userId = connectionCamera();
                std::cout << "userId= " << userId << std::endl;
                if(userId == -1)
                    return printErrorAndExit(ERROR_CONNEXION);
                NET_DVR_PTZControlWithSpeed_Other(userId, 1, PAN_RIGHT, 0, 2);
                
                return SUCCESS;
                     
            }
            return printErrorAndExit(ERROR_ARGUMENTS);
        }
        
        ///////////// Faire revenir la caméra au Preset 1 ////////////////////////////////
        if(!strcmp (argv[NUM_ARG_TARGET],"HOME")){
            cout << "COMMANDE HOME" << endl;
            userId = connectionCamera();
            std::cout << "userId= " << userId << std::endl;   
            
            PreviewInfo.dwLinkMode = 0; // TCP
            PreviewInfo.lChannel = 1;
            PreviewInfo.bBlocked = 0;
            PreviewInfo.bPassbackRecord = 1;
            PreviewInfo.byPreviewMode = 0;
            PreviewInfo.byProtoType = 0;
            PreviewInfo.hPlayWnd = (HWND) NULL;
            PreviewInfo.dwStreamType = 0;
            
            lRealPlayHandle = NET_DVR_RealPlay_V40(userId, &PreviewInfo, NULL, NULL);
            cout << "lRealPlayHandle= "<< lRealPlayHandle << endl;
            if(userId == -1)
                return printErrorAndExit(ERROR_CONNEXION);

            NET_DVR_PTZPreset(lRealPlayHandle, GOTO_PRESET, DWORD(1));
            
            // cout << NET_DVR_GetErrorMsg() << endl;
            return SUCCESS;
                    
        }
        ///////////// Démarrage de la connexion voix////////////////////////////////
        if(!strcmp (argv[NUM_ARG_TARGET],"VOICE")){
            cout << "COMMANDE VOICE" << endl;
            userId = connectionCamera();
            std::cout << "userId= " << userId << std::endl;   
            
            NET_DVR_StartVoiceCom(userId,&callbackVoixDieu,DWORD(0));
            NET_DVR_SetVoiceComClientVolume(voiceComHandle, 50);
            while(1);

            //cout << NET_DVR_GetErrorMsg() << endl;
            return SUCCESS;
        }

        return printErrorAndExit(ERROR_ARGUMENTS);
    ///////////// STOP les mouvements ou Voix////////////////////////////////
    }else if(!strcmp (argv[NUM_ARG_COMMAND],"STOP")){
        //COMMAND STOP

        std::cout << "Commande STOP" << std::endl;
        userId = connectionCamera();
        if(userId == -1)
            return printErrorAndExit(ERROR_CONNEXION);

        if(argc == 4 && (!strcmp (argv[NUM_ARG_TARGET],"VOICE")))
            NET_DVR_StopVoiceCom(LONG(0));
        else
            NET_DVR_PTZControlWithSpeed_Other(userId, 1, PAN_LEFT, 1, 2);

        return SUCCESS;
                     
    }
    ///////////// Vérifier le numéro de série de la caméra ////////////////////////////////
    else if(!strcmp (argv[NUM_ARG_COMMAND],"CHECK")){
        std::cout << "Commande CHECK" << std::endl;
        userId = connectionCamera();
        if(userId == -1)
            return printErrorAndExit(ERROR_CONNEXION);
        cout << struDeviceInfo.sSerialNumber << endl;

        if(!strcmp (argv[NUM_ARG_TARGET],(const char*) struDeviceInfov40.struDeviceV30.sSerialNumber))
            return SUCCESS;
        else 
            return printErrorAndExit(ERROR_LICENCE);
    }
    else {
        //ERREUR COMMANDE
        return printErrorAndExit(ERROR_ARGUMENTS);
    }
    std::cout << "fin" << std::endl;

    return SUCCESS;
}

/**
 * Etablit la connexion avec la camera 
 * 
 * Retourne l'identifiant de connexion à la camera à utiliser à chaque commande
 */
LONG connectionCamera(){

    std::cout << "ip= "   << ip     << std::endl;
    std::cout << "login= "<< login  << std::endl;
    std::cout << "mdp= "  << mdp    << std::endl;
    std::cout << "port= " << port   << std::endl;

    strcpy(loginInfo.sDeviceAddress,ip.c_str());
    loginInfo.wPort = port;
    strcpy(loginInfo.sUserName,login.c_str());
    strcpy(loginInfo.sPassword,mdp.c_str());
    loginInfo.bUseAsynLogin = 0;


    NET_DVR_Init();
    NET_DVR_SetConnectTime(1000, 10);
    NET_DVR_SetReconnect(1200, true);
    NET_DVR_SetLogToFile(0, (char*)"",FALSE);

    return NET_DVR_Login_V40(&loginInfo,&struDeviceInfov40);
}

/**
 * Affiche l'erreur retourne le code d'erreur
 */ 
int printErrorAndExit(int errorCode){
    switch(errorCode){
        case ERROR_ARGUMENTS:
            cout << "Arguments incorrects, utilisez comme ceci:"<< endl;

            cout <<  "./executable NUMERO_CAMERA COMMAND TARGET OPTION"<< endl;
            cout <<  "COMMANDS = [ START  , STOP ,  CHECK]"<< endl;
            cout <<  "TARGET =  [  ZOOM   ,    TILT   ,      PAN      , HOME,  VOICE]"<< endl;
            cout <<  "OPTIONS = [[IN,OUT] , [UP,DOWN] , [LEFT,RIGHT]]"<< endl;
            
            cout <<  "Exemple:" << endl;
            cout << "./executable START ZOOM IN"<< endl;
            break;
        case ERROR_CONFIG_FILE_NOT_FOUND:
            cout << "Fichier de configuration introuvable"<<endl;
            break;
        case ERROR_IN_CONFIG_FILE:
            cout << "Fichier de configuration mal formatté"<<endl;
            break;
        case ERROR_CONNEXION:
            cout << "Erreur de connexion à la camera"<<endl;
            break;
        case ERROR_LICENCE:
            cout << "La licence de la camera n'est pas bonne"<<endl;
            break;
    }
    exit(errorCode);
    cout << "on est pas censé voir ça" << endl;
    return errorCode;
}


/**
 * Lit le contenu du fichier config.ini situe dans le même repertoire
 * 
 * Remplit les variables 
 *  ip
 *  login
 *  mdp
 *  licCam
 *  port
 * 
 * Termine le programme avec une erreur si le fichier est introuvable ou mal formatté
 */
int lireFichierIni(char* argv[]){

    bool check[5] = {false,false,false,false,false};

    string tp;
    string numeroCam(argv[NUM_ARG_NUM_CAM]);
    fstream newfile;
    newfile.open(CONFIG_FILE_PATH,ios::in); //open a file to perform read operation using file object
    if (newfile.is_open()){   //checking whether the file is open
        while(getline(newfile, tp)){ //read data from file object and put it into string.
            if(tp.find("Cam" + numeroCam) != string::npos){
                //La camera existe bien dans le fichier config
                while(getline(newfile,tp)){
                    //Si on arrive à la configuration d'une autre camera on se stoppe sur le champ de blé
                    if(tp.find("[Cam" ) != string::npos)
                        break;

                    if(tp.find("PortCam") != string::npos){
                        tp = tp.substr(10,tp.length()-1);
                        try{
                            port = (WORD) stoi(tp);
                        }catch(exception &e){
                            cout << "Port invalide" << endl;
                            return printErrorAndExit(ERROR_IN_CONFIG_FILE);
                        }
                        check[0] = true;
                        continue;
                    }

                    if(tp.find("IpCam") != string::npos){
                        tp = tp.substr(8,tp.length()-1);
                        ip = tp;
                        check[1] = true;
                        continue;
                    }

                    if(tp.find("IdentifiantCam") != string::npos){
                        tp = tp.substr(tp.find("\"")+1,tp.find_last_of("\"")- tp.find("\"") -1);
                        login = tp;
                        check[2] = true;
                        continue;
                    }

                    if(tp.find("PasswordCam") != string::npos){
                        tp = tp.substr(tp.find("\"")+1,tp.find_last_of("\"") - tp.find("\"") -1);
                        mdp = tp;
                        check[3] = true;
                        continue;
                    }

                    if(tp.find("LicCam") != string::npos){
                        tp = tp.substr(tp.find("\"")+1,tp.find_last_of("\"") - tp.find("\"") -1);
                        licCam = tp;
                        check[4] = true;
                        continue;
                    }
                }
            }
      }
      newfile.close(); //close the file object.
        bool fullCheck = true;
        for(int i = 0 ; i < 5 ; ++i){
            if(check[i] == false){
               fullCheck = false;
               switch(i){
                   case 0: cout << "Port de la camera " << numeroCam << " non renseigne" << endl;break;
                   case 1: cout << "IP de la camera " << numeroCam << " non renseigne" << endl;break;
                   case 2: cout << "Identifiant de la camera " << numeroCam << " non renseigne" << endl;break;
                   case 3: cout << "Mot de passe de la camera " << numeroCam << " non renseigne" << endl;break;
                   case 4: cout << "Numero de serie de la camera " << numeroCam << " non renseigne" << endl;break;
               }
            }
        } 
        if(!fullCheck)
            printErrorAndExit(ERROR_IN_CONFIG_FILE);
        return SUCCESS;
    }else{
       return printErrorAndExit(ERROR_CONFIG_FILE_NOT_FOUND);
    }
}
/**
 * Fonction vérifiant que la licence de la caméra est conforme à celle entrée dans le fichier config.ini
 * 
 * Renvoie SUCCESS ou ERROR_LICENCE
 */
int verifierLicence(){
    return SUCCESS;
}

/**
 * Fonction appelée lors de l'acknowledgement de la caméra pour le flux audio qu'on lui envoie
 */
void callbackVoixDieu(LONG lVoiceComHandle, char *pRecvDataBuffer, DWORD dwBufSize, BYTE byAudioFlag, DWORD dwUser){
    /*
    cout << lVoiceComHandle << endl;

    voiceComHandle = lVoiceComHandle;
    std::ofstream outfile;

    outfile.open("test", std::ios_base::app); // append instead of overwrite
    cout << pRecvDataBuffer;
    outfile << pRecvDataBuffer; 
    */
    return;
}