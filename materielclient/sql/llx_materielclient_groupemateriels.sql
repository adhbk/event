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


CREATE TABLE llx_materielclient_groupemateriels(
	-- BEGIN MODULEBUILDER FIELDS
	rowid integer AUTO_INCREMENT PRIMARY KEY NOT NULL, 
	date_creation datetime NOT NULL, 
	tms timestamp, 
	fk_user_creat integer NOT NULL, 
	fk_user_modif integer, 
	import_key varchar(14), 
	fk_materiel1 integer, 
	fk_materiel2 integer, 
	fk_materiel3 integer, 
	fk_materiel4 integer, 
	fk_materiel5 integer, 
	fk_materiel6 integer, 
	fk_materiel7 integer, 
	fk_materiel8 integer, 
	ref varchar(128) NOT NULL, 
	fk_materiel9 integer
	-- END MODULEBUILDER FIELDS
) ENGINE=innodb;