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


-- BEGIN MODULEBUILDER INDEXES
ALTER TABLE llx_materielclient_intervention ADD INDEX idx_materielclient_intervention_rowid (rowid);
ALTER TABLE llx_materielclient_intervention ADD CONSTRAINT llx_materielclient_intervention_fk_user_creat FOREIGN KEY (fk_user_creat) REFERENCES user(rowid);
-- END MODULEBUILDER INDEXES

--ALTER TABLE llx_materielclient_intervention ADD UNIQUE INDEX uk_materielclient_intervention_fieldxy(fieldx, fieldy);

--ALTER TABLE llx_materielclient_intervention ADD CONSTRAINT llx_materielclient_intervention_fk_field FOREIGN KEY (fk_field) REFERENCES llx_materielclient_myotherobject(rowid);

