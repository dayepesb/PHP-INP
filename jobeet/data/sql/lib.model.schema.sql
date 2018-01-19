
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- jobeet_category
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_category`;

CREATE TABLE `jobeet_category`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`slug` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `jobeet_category_U_1` (`name`),
	UNIQUE KEY `jobeet_category_U_2` (`slug`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- jobeet_job
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_job`;


CREATE TABLE `jobeet_job`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`category_id` INTEGER  NOT NULL,
	`type` VARCHAR(255),
	`company` VARCHAR(255)  NOT NULL,
	`logo` VARCHAR(255),
	`url` VARCHAR(255),
	`position` VARCHAR(255)  NOT NULL,
	`location` VARCHAR(255)  NOT NULL,
	`description` TEXT  NOT NULL,
	`how_to_apply` TEXT  NOT NULL,
	`token` VARCHAR(255)  NOT NULL,
	`is_public` TINYINT default 1 NOT NULL,
	`is_activated` TINYINT default 0 NOT NULL,
	`email` VARCHAR(255)  NOT NULL,
	`expires_at` DATETIME  NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	UNIQUE KEY `jobeet_job_U_1` (`token`),
	INDEX `jobeet_job_FI_1` (`category_id`),
	CONSTRAINT `jobeet_job_FK_1`
		FOREIGN KEY (`category_id`)
		REFERENCES `jobeet_category` (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- jobeet_affiliate
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_affiliate`;


CREATE TABLE `jobeet_affiliate`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`url` VARCHAR(255)  NOT NULL,
	`email` VARCHAR(255)  NOT NULL,
	`token` VARCHAR(255)  NOT NULL,
	`is_active` TINYINT default 0 NOT NULL,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	UNIQUE KEY `jobeet_affiliate_U_1` (`email`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- jobeet_category_affiliate
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_category_affiliate`;


CREATE TABLE `jobeet_category_affiliate`
(
	`category_id` INTEGER  NOT NULL,
	`affiliate_id` INTEGER  NOT NULL,
	PRIMARY KEY (`category_id`,`affiliate_id`),
	CONSTRAINT `jobeet_category_affiliate_FK_1`
		FOREIGN KEY (`category_id`)
		REFERENCES `jobeet_category` (`id`)
		ON DELETE CASCADE,
	INDEX `jobeet_category_affiliate_FI_2` (`affiliate_id`),
	CONSTRAINT `jobeet_category_affiliate_FK_2`
		FOREIGN KEY (`affiliate_id`)
		REFERENCES `jobeet_affiliate` (`id`)
		ON DELETE CASCADE
)Engine=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;


# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- sf_guard_group
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_group`;


CREATE TABLE `sf_guard_group`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`description` TEXT,
	PRIMARY KEY (`id`),
	UNIQUE KEY `sf_guard_group_U_1` (`name`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_guard_permission
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_permission`;


CREATE TABLE `sf_guard_permission`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`description` TEXT,
	PRIMARY KEY (`id`),
	UNIQUE KEY `sf_guard_permission_U_1` (`name`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_guard_group_permission
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_group_permission`;


CREATE TABLE `sf_guard_group_permission`
(
	`group_id` INTEGER  NOT NULL,
	`permission_id` INTEGER  NOT NULL,
	PRIMARY KEY (`group_id`,`permission_id`),
	CONSTRAINT `sf_guard_group_permission_FK_1`
		FOREIGN KEY (`group_id`)
		REFERENCES `sf_guard_group` (`id`)
		ON DELETE CASCADE,
	INDEX `sf_guard_group_permission_FI_2` (`permission_id`),
	CONSTRAINT `sf_guard_group_permission_FK_2`
		FOREIGN KEY (`permission_id`)
		REFERENCES `sf_guard_permission` (`id`)
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_guard_user
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_user`;


CREATE TABLE `sf_guard_user`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(128)  NOT NULL,
	`algorithm` VARCHAR(128) default 'sha1' NOT NULL,
	`salt` VARCHAR(128)  NOT NULL,
	`password` VARCHAR(128)  NOT NULL,
	`created_at` DATETIME,
	`last_login` DATETIME,
	`is_active` TINYINT default 1 NOT NULL,
	`is_super_admin` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `sf_guard_user_U_1` (`username`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_guard_user_permission
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_user_permission`;


CREATE TABLE `sf_guard_user_permission`
(
	`user_id` INTEGER  NOT NULL,
	`permission_id` INTEGER  NOT NULL,
	PRIMARY KEY (`user_id`,`permission_id`),
	CONSTRAINT `sf_guard_user_permission_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE,
	INDEX `sf_guard_user_permission_FI_2` (`permission_id`),
	CONSTRAINT `sf_guard_user_permission_FK_2`
		FOREIGN KEY (`permission_id`)
		REFERENCES `sf_guard_permission` (`id`)
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_guard_user_group
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_user_group`;


CREATE TABLE `sf_guard_user_group`
(
	`user_id` INTEGER  NOT NULL,
	`group_id` INTEGER  NOT NULL,
	PRIMARY KEY (`user_id`,`group_id`),
	CONSTRAINT `sf_guard_user_group_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE,
	INDEX `sf_guard_user_group_FI_2` (`group_id`),
	CONSTRAINT `sf_guard_user_group_FK_2`
		FOREIGN KEY (`group_id`)
		REFERENCES `sf_guard_group` (`id`)
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- sf_guard_remember_key
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sf_guard_remember_key`;


CREATE TABLE `sf_guard_remember_key`
(
	`user_id` INTEGER  NOT NULL,
	`remember_key` VARCHAR(32),
	`ip_address` VARCHAR(50)  NOT NULL,
	`created_at` DATETIME,
	PRIMARY KEY (`user_id`,`ip_address`),
	CONSTRAINT `sf_guard_remember_key_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE CASCADE
)Engine=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
