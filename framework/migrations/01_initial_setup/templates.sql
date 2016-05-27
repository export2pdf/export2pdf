CREATE TABLE IF NOT EXISTS `{prefix}templates`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(100) NOT NULL DEFAULT 'Template',
  `name` VARCHAR(255),
  `pdf_file_id` INT(15),
  `flatten` TINYINT(3) NOT NULL DEFAULT '0',
  `optimize` INT(5) NOT NULL DEFAULT '0',
  `filename` VARCHAR(255),
  `format` VARCHAR(255) NOT NULL DEFAULT 'pdf',
  `password` VARCHAR(255),
  `addon` VARCHAR(255),
  `form` VARCHAR(255),
  `form_primary_field` VARCHAR(255),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
