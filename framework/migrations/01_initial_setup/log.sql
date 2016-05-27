CREATE TABLE IF NOT EXISTS `{prefix}logs`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255),
  `timestamp` BIGINT(20),
  `request_id` VARCHAR(255),
  `group` VARCHAR(255),
  `data` LONGTEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
