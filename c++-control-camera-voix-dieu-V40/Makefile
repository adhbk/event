#### D. GUERINEAU 29/06/2021
#### g++ main.cpp -o test

MAKEFILE      = Makefile

####### Compiler, tools and options

ARCH		  = linux64
CC            = gcc
CXX           = g++
DEFINES       = 
CFLAGS        = -Wall -O
CXXFLAGS      = -Wall -O
INCPATH       = -I./HIKvision.h
DEL_FILE      = rm -f
CHK_DIR_EXISTS= test -d
MKDIR         = mkdir -p
COPY          = cp -f
COPY_FILE     = cp -f
COPY_DIR      = cp -f -R
INSTALL_FILE  = install -m 644 -p
INSTALL_PROGRAM = install -m 755 -p
INSTALL_DIR   = cp -f -R
DEL_FILE      = rm -f
SYMLINK       = ln -f -s
DEL_DIR       = rmdir
MOVE          = mv -f
TAR           = tar -cf
COMPRESS      = gzip -9f
DISTNAME      = test
DISTDIR = ./
LINK          = g++
LIBS          = -L./ -L../ -L./lib/ -L./lib/HCNetSDKCom -lAudioRender -lHCCore -lhcnetsdk -lhpr -lPlayCtrl -lSuperRender -lanalyzedata -lHCAlarm -lHCCoreDevCfg -lHCDisplay -lHCGeneralCfgMgr -lHCIndustry -lHCPlayBack -lHCPreview -lHCVoiceTalk -liconv2 -lStreamTransClient -lSystemTransform
AR            = ar cqs
RANLIB        =
SED           = sed
STRIP         = strip
#Ou chercher les librairies .so au runtime
LFLAGS = -Wl,-rpath -Wl,./lib

####### Output directory

OBJECTS_DIR   = ./

####### Files

SOURCES       = main.cpp

OBJECTS       = main.o

TARGET 		  = commandeCamera

####### Build rules

all: $(TARGET)
    

$(TARGET): $(OBJECTS)  
	$(LINK) $(LFLAGS) -o $(TARGET) $(OBJECTS) $(OBJCOMP) $(LIBS)

    

.o: 
	$(CXX) -c $(CXXFLAGS) $(INCPATH) -o "$@" "$<"

clean: 
	$(DEL_FILE) $(OBJECTS)

mrproper: clean
	$(DEL_FILE) $(TARGET)
    
    
    


