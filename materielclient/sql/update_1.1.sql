/*
DROP PROCEDURE IF EXISTS `ajoutColonnecontrats`;
DELIMITER //
CREATE PROCEDURE `ajoutColonnecontrats`()
BEGIN
  DECLARE CONTINUE HANDLER FOR SQLEXCEPTION BEGIN END;
  ALTER TABLE `llx_materielclient_materiel` ADD COLUMN `contrats` VARCHAR(2048);
END //
DELIMITER ;
CALL `ajoutColonnecontrats`();
DROP PROCEDURE `ajoutColonnecontrats`;
*/
ALTER TABLE `llx_materielclient_materiel` ADD COLUMN `contrats` VARCHAR(2048);
ALTER TABLE `llx_materielclient_materiel` ADD COLUMN `fk_fournisseur` INTEGER;
ALTER TABLE `llx_materielclient_materiel` ADD COLUMN `localisation` VARCHAR(128);
