################################################################################
#
#   Release Notes:
#
################################################################################
######################## SCHEMA CHANGES & DATA MIGRATION #######################
################################################################################

DROP TABLE IF EXISTS chargeback
;

DROP TABLE IF EXISTS stakeholder
;

CREATE TABLE IF NOT EXISTS stakeholder (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    title VARCHAR(40) NOT NULL,
    email VARCHAR(40) NOT NULL,
    customer_id BIGINT(20) UNSIGNED NOT NULL,
    deleted TINYINT(1) UNSIGNED NOT NULL,
    UNIQUE KEY (customer_id, name),
    CONSTRAINT fk_stakeholder_customer_id
    FOREIGN KEY (customer_id)
    REFERENCES customer (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB
;

alter table stakeholder add column email varchar (40) not null after title;

CREATE TABLE IF NOT EXISTS chargeback (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    stakeholder_id SMALLINT UNSIGNED NOT NULL,
    account_id BIGINT(20) UNSIGNED NOT NULL,
    service_provider_product_id BIGINT(20) UNSIGNED NOT NULL,
    UNIQUE (account_id, service_provider_product_id),
    CONSTRAINT fk_chargeback_stakeholder_id
    FOREIGN KEY (stakeholder_id)
    REFERENCES stakeholder (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_chargeback_account_id
    FOREIGN KEY (account_id)
    REFERENCES account (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_chargeback_service_provider_product_id
    FOREIGN KEY (service_provider_product_id)
    REFERENCES service_product (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB
;

################################################################################
############################ DATA INSERTS & CHANGES ############################
################################################################################



################################################################################
################################# VIEW CHANGES #################################
################################################################################

SOURCE db/views.sql
