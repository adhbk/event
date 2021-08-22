-- Copyright (C) ---Put here your own copyright and developer email---
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see http://www.gnu.org/licenses/.


CREATE TABLE llx_materielclient_materiel(
	-- BEGIN MODULEBUILDER FIELDS
	rowid integer AUTO_INCREMENT PRIMARY KEY NOT NULL, 
	date_creation datetime NOT NULL, 
	tms timestamp, 
	fk_user_creat integer NOT NULL, 
	fk_user_modif integer, 
	import_key varchar(14), 
	ref varchar(128) NOT NULL, 
	marque varchar(128), 
	modele varchar(128), 
	type_clim varchar(128), 
	gaz varchar(128), 
	num_serie varchar(128), 
	date_installation date, 
	duree_garantie varchar(128), 
	puissance varchar(128), 
	charge_totale_kg varchar(128), 
	tonnage_equivalent_co2 varchar(128), 
	date_demantelement date, 
	notes text, 
	pose_par_nous integer, 
	emplacement integer, 
	statut integer, 
	fk_soc integer, 
	localisation varchar(128), 
	contrats varchar(2048), 
	fk_fournisseur integer
	-- END MODULEBUILDER FIELDS
) ENGINE=innodb;