CREATE TABLE IF NOT EXISTS `{prefix}file_pages`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pdf_file_id` INT(15),
  `number` INT(15),
  `width` INT(15),
  `height` INT(15),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
