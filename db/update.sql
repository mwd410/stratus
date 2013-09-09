########################################################
#################### SCHEMA CHANGES ####################
########################################################

ALTER TABLE `users`
ADD UNIQUE KEY (`email_address`),
ADD UNIQUE KEY (`user_name`);

CREATE TABLE IF NOT EXISTS `dashboard` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `name` VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_dashboard_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`user_id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS `widget_row` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dashboard_id` BIGINT(20) UNSIGNED NOT NULL,
    `height` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_widget_row_dashboard1`
    FOREIGN KEY (`dashboard_id`)
    REFERENCES `dashboard` (`id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS `widget_column` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `widget_row_id` BIGINT(20) UNSIGNED NOT NULL,
    `column_order` INT(5) UNSIGNED NOT NULL,
    `flex` INT(5) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`widget_row_id`, `column_order`),
    CONSTRAINT `fk_widget_column_widget_row1`
    FOREIGN KEY (`widget_row_id`)
    REFERENCES `widget_row` (`id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS `widget_type` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS `widget` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `widget_type_id` BIGINT(20) UNSIGNED NOT NULL,
    `widget_column_id` BIGINT(20) UNSIGNED NOT NULL,
    `widget_order` INT(5) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`widget_column_id`, `widget_order`),
    CONSTRAINT `fk_widget_widget_type1`
    FOREIGN KEY (`widget_type_id`)
    REFERENCES `widget_type` (`id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT `fk_widget_widget_column1`
    FOREIGN KEY (`widget_column_id`)
    REFERENCES `widget_column` (`id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS `widget_attribute_type` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NOT NULL,
    `single` TINYINT(1) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `widget_attribute` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `widget_id` BIGINT(20) UNSIGNED NOT NULL,
    `widget_attribute_type_id` BIGINT(20) UNSIGNED NOT NULL,
    `value` VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_widget_attribute_widget1`
    FOREIGN KEY (`widget_id`)
    REFERENCES `widget` (`id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT `fk_widget_attribute_widget_attribute_type1`
    FOREIGN KEY (`widget_attribute_type_id`)
    REFERENCES `widget_attribute_type` (`id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS `widget_default_attribute` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `widget_type_id` BIGINT(20) UNSIGNED NOT NULL,
    wi
);

########################################################
##################### DATA INSERTS #####################
########################################################



########################################################
##################### VIEW CHANGES #####################
########################################################