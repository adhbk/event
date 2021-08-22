#!/bin/bash
cd /home/ffmpeg/Adrien_Stage_2021/Tests_Node/c++-control-camera
for i in {1..300}
do
   if [ $(( $i % 3 )) = 1 ]
   then 
      ./commandeCamera 1 STOP
   elif [ $(( $i % 3 )) = 2 ]
   then
     ./commandeCamera 1 START PAN LEFT
   else
      ./commandeCamera 1 START PAN RIGHT
   fi
   echo $i
   sleep 3
done
