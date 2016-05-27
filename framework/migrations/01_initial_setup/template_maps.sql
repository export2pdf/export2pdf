CREATE TABLE IF NOT EXISTS `{prefix}template_maps`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_id` INT(15),
  `pdf_field_id` INT(15),
  `source_id` VARCHAR(255),
  `formatting` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
