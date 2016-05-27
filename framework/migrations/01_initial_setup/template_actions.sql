CREATE TABLE IF NOT EXISTS `{prefix}template_actions`
(
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_id` INT(15),
  `action_type` VARCHAR(255),
  `data` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`id`)
) CHARSET={charset}
