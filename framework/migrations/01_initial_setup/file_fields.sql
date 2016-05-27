CREATE TABLE IF NOT EXISTS `{prefix}file_fields`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pdf_page_id` INT(15),
  `pdf_file_id` INT(15),
  `name` VARCHAR(255),
  `x` FLOAT(20,7),
  `y` FLOAT(20,7),
  `width` FLOAT(20, 7),
  `height` FLOAT(20, 7),
  `type` VARCHAR(255),
  `options` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
