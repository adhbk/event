#! /bin/bash

cd ~/dolibarr/
echo "# MaterielClient 
#
# Goal:    Permet de gérer des matériels des interventions et des ensembles de matériels liés à un Tiers
# Version: $1
# Author:  Copyright 2021 - Adrien Prévost
# Licence: GPL
# Install: Just unpack content of module package in Dolibarr directory.
# Setup:   Go on Dolibarr setup - modules to enable module.
#
# Files in module" | tee build/makepack-MaterielClient.conf

du -a . | grep htdocs/custom | awk '{print $2}' | sed 's/.//'  | grep -F . >> build/makepack-MaterielClient.conf

cd build
echo MaterielClient | perl makepack-dolibarrmodule.pl