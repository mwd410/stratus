CREATE TABLE `customer` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`) #optional, but I think we should do this.
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `user` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_name` VARCHAR(40) NOT NULL,
    `password` VARCHAR(32) NOT NULL,
    `email_address` VARCHAR(60) NOT NULL,
    `customer_id` BIGINT(20) UNSIGNED NOT NULL,
    `deleted` INT(2) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`user_name`),
    UNIQUE KEY (`email_address`),
    KEY `fk_user_customer1` (`customer_id`),
    CONSTRAINT `fk_user_customer1` FOREIGN KEY (`customer_id`)
    REFERENCES `customer` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `account` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    #TODO: varchar
    `account_id` BIGINT(20) NOT NULL,
    `customer_id` BIGINT(20) UNSIGNED NOT NULL,
    `account_name` VARCHAR(40) NOT NULL,
    `aws_key` VARCHAR(40) NOT NULL,
    `secret_key` VARCHAR(80) NOT NULL,
    `deleted` INT(2) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY (`account_id`),
    # this will allow mysql to get the account_id
    # without reading from the table. much faster.
    KEY `k_account_account_id1` (`id`, `account_id`),
    KEY `fk_account_customer1` (`customer_id`),
    CONSTRAINT `fk_account_customer1` FOREIGN KEY (`customer_id`)
    REFERENCES `customer` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `instance_info` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `instance_id` VARCHAR(40) NOT NULL,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `os` VARCHAR(40) DEFAULT NULL,
    `region` VARCHAR(40) NOT NULL,
    `image_id` VARCHAR(40) NOT NULL,
    `state` VARCHAR(40) NOT NULL,
    `instance_size` VARCHAR(40) NOT NULL,
    `virt_type` VARCHAR(40) DEFAULT NULL,
    `architecture` VARCHAR(40) DEFAULT NULL,
    `launch_date` DATE DEFAULT NULL,
    `tag` VARCHAR(40) DEFAULT NULL,
    `history_date` DATE NOT NULL,
    `last_sync` VARCHAR(20) NOT NULL,
    `running_hours` INT(10) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`instance_id`, `history_date`),
    KEY `fk_instance_info_account1` (`account_id`),
    CONSTRAINT `fk_instance_info_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `instance_pricing` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `region` VARCHAR(40) NOT NULL,
    `size` VARCHAR(40) NOT NULL,
    `os` VARCHAR(40) NOT NULL,
    `utilization` VARCHAR(40) NOT NULL,
    `hourly_rate` FLOAT DEFAULT NULL,
    `upfront_rate` FLOAT DEFAULT NULL,
    `reservation` VARCHAR(40) NOT NULL,
    `currency` VARCHAR(40) NOT NULL,
    `term` VARCHAR(40) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE KEY (`region`, `size`, `os`, `reservation`, `term`, `utilization`)
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `reserved_info` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `reservation_id` VARCHAR(40) NOT NULL,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `region` VARCHAR(40) NOT NULL,
    `instance_size` VARCHAR(40) NOT NULL,
    `duration` INT(11) DEFAULT NULL,
    `fixed_price` FLOAT DEFAULT NULL,
    `usage_price` FLOAT DEFAULT NULL,
    `description` VARCHAR(100) DEFAULT NULL,
    `qty` INT(11) DEFAULT NULL,
    `state` VARCHAR(40) DEFAULT NULL,
    `history_date` DATE NOT NULL,
    `start_date` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`reservation_id`, `history_date`),
    CONSTRAINT `fk_reserved_info_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `volume_info` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `volume_id` VARCHAR(40) NOT NULL,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `instance_id` VARCHAR(40) DEFAULT NULL,
    `mount_point` VARCHAR(40) NOT NULL,
    `snapshot` VARCHAR(40) NOT NULL,
    `region` VARCHAR(40) NOT NULL,
    `size` FLOAT NOT NULL,
    `status` VARCHAR(40) NOT NULL,
    `attach_date` DATE DEFAULT NULL,
    `history_date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`volume_id`, `history_date`),
    KEY `fk_volume_info_account1` (`account_id`),
    CONSTRAINT `fk_volume_info_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `volume_pricing` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `region` VARCHAR(40) NOT NULL,
    `cost_type` VARCHAR(40) NOT NULL,
    `volume_type` VARCHAR(40) NOT NULL,
    `monthly_rate` FLOAT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`region`, `cost_type`, `volume_type`)
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `instance_history` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `region` VARCHAR(40) NOT NULL,
    `instance_size` VARCHAR(40) NOT NULL,
    `running_qty` INT(5) NOT NULL,
    `running_hours` INT(10) NOT NULL,
    `os` VARCHAR(40) NOT NULL,
    `reservation` VARCHAR(40) NOT NULL,
    `hourly_rate` FLOAT NOT NULL,
    `history_date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`account_id`, `history_date`, `region`, `instance_size`, `os`, `reservation`),
    KEY `instance_history_account1` (`account_id`),
    CONSTRAINT `instance_history_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `volume_history` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `region` VARCHAR(40) NOT NULL,
    `total_size` FLOAT NOT NULL,
    `total_qty` INT NOT NULL,
    `status` VARCHAR(40) NOT NULL,
    `monthly_rate` FLOAT NOT NULL,
    `history_date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`account_id`, `history_date`, `region`, `status`),
    KEY `volume_history_account1` (`account_id`),
    CONSTRAINT `volume_history_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `performance_info` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `instance_id` BIGINT(20) UNSIGNED NOT NULL,
    `region` VARCHAR(40) NOT NULL,
    `stat_type` VARCHAR(40) NOT NULL,
    `stat_datapoint` VARCHAR(40) NOT NULL,
    `stat_value` FLOAT NOT NULL,
    `history_date` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`instance_id`, `region`, `stat_type`, `stat_datapoint`, `history_date`),
    KEY `fk_performance_info_instance_info1` (`instance_id`),
    CONSTRAINT `fk_performance_info_instance_info1` FOREIGN KEY (`instance_id`)
    REFERENCES `instance_info` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `billing_info` (
    `payer_account_id` BIGINT(20) NOT NULL,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `record_type` VARCHAR(40) NOT NULL,
    `product_name` VARCHAR(128) NOT NULL,
    `rate_id` BIGINT(20) NOT NULL,
    `subscription_id` BIGINT(20) NOT NULL,
    `price_id` BIGINT(20) NOT NULL,
    `usage_type` VARCHAR(128) NOT NULL,
    `operation` VARCHAR(128) NOT NULL,
    `region` VARCHAR(40) NOT NULL,
    `reserved` VARCHAR(2) NOT NULL,
    `description` TEXT(255) NOT NULL,
    `start_date` DATETIME NOT NULL,
    `end_date` DATETIME NOT NULL,
    `usage_quantity` FLOAT NOT NULL,
    `rate` FLOAT NOT NULL,
    `cost` FLOAT NOT NULL,
    PRIMARY KEY (`account_id`, `rate_id`, `subscription_id`, `start_date`, `end_date`, `region`, `operation`),
    KEY `fk_billing_info_account1` (`account_id`),
    CONSTRAINT `fk_billing_info_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `billing_totals` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `master_account_id` BIGINT(20) UNSIGNED NOT NULL,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `record_type` VARCHAR(40) NOT NULL,
    `description` TEXT(255) NOT NULL,
    `cost` FLOAT NOT NULL,
    `bill_month` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`master_account_id`, `account_id`, `record_type`, `bill_month`),
    KEY `fk_billing_totals_account1` (`master_account_id`),
    CONSTRAINT `fk_billing_totals_account1` FOREIGN KEY (`master_account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `billing_history` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `payer_account_id` BIGINT(20) NOT NULL,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `product_name` VARCHAR(128) NOT NULL,
    `rate_id` BIGINT(20) NOT NULL,
    `subscription_id` BIGINT(20) NOT NULL,
    `price_id` BIGINT(20) NOT NULL,
    `usage_type` VARCHAR(128) NOT NULL,
    `operation` VARCHAR(128) NOT NULL,
    `region` VARCHAR(40) NOT NULL,
    `reserved` VARCHAR(2) NOT NULL,
    `description` TEXT(255) NOT NULL,
    `usage_quantity` FLOAT NOT NULL,
    `rate` FLOAT NOT NULL,
    `cost` FLOAT NOT NULL,
    `history_date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`account_id`, `rate_id`, `subscription_id`, `history_date`, `region`, `operation`),
    KEY `fk_billing_history_account1` (`account_id`),
    CONSTRAINT `fk_billing_history_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `master_account` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `customer_id` BIGINT(20) UNSIGNED NOT NULL,
    `billing_bucket` VARCHAR(128) NOT NULL,
    `billing_file` VARCHAR(255) DEFAULT NULL,
    `imported_billing_file` VARCHAR(255) DEFAULT NULL,
    `sync_date` DATE DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`customer_id`),
    KEY `fk_master_account_customer1` (`customer_id`),
    CONSTRAINT `fk_master_account_customer1` FOREIGN KEY (`customer_id`)
    REFERENCES `customer` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    KEY `fk_master_account_account1` (`account_id`),
    CONSTRAINT `fk_master_account_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `billing_file_details` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `billing_file` VARCHAR(255) NOT NULL,
    `last_modified` DATETIME NOT NULL,
    `import_date` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`account_id`, `billing_file`),
    KEY `fk_billing_file_details_account1` (`account_id`),
    CONSTRAINT `fk_billing_file_details_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `billing_reservations` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `payer_account_id` BIGINT(20) NOT NULL,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `product_name` VARCHAR(128) NOT NULL,
    `rate_id` BIGINT(20) NOT NULL,
    `reserved` VARCHAR(2) NOT NULL,
    `description` TEXT(255) NOT NULL,
    `usage_quantity` FLOAT NOT NULL,
    `cost` FLOAT NOT NULL,
    `bill_month` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`account_id`, `rate_id`),
    KEY `fk_billing_reservations_account1` (`account_id`),
    CONSTRAINT `fk_billing_reservations_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;


CREATE TABLE `chargeback` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `account_id` BIGINT(20) UNSIGNED NOT NULL,
    `product_name` VARCHAR(128) NOT NULL,
    `stake_holder` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`account_id`, `product_name`, `stake_holder`),
    KEY `fk_chargeback_account1` (`account_id`),
    CONSTRAINT `fk_chargeback_account1` FOREIGN KEY (`account_id`)
    REFERENCES `account` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB DEFAULT CHARSET = `latin1`;
