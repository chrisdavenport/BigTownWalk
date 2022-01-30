ALTER TABLE `#__bigtownwalk_walks` ADD COLUMN `checked_out` INT(11) NOT NULL AFTER `access`;
ALTER TABLE `#__bigtownwalk_walks` ADD COLUMN `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out`;
