CREATE TABLE IF NOT EXISTS `sessions` (
	`id` VARCHAR(32) NOT NULL DEFAULT '',
	`data` MEDIUMTEXT NULL,
	`last_activity` BIGINT(20) UNSIGNED NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `id` (`id`)
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;
