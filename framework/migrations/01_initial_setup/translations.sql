CREATE TABLE IF NOT EXISTS `{prefix}translations`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `language` VARCHAR(50),
  `original` VARCHAR(500),
  `translated` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
