CREATE TABLE IF NOT EXISTS `{prefix}template_options`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_id` INT(15),
  `key` VARCHAR(100),
  `value` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
