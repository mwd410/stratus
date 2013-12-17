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

CREATE TABLE IF NOT EXISTS chargeback_unit (
    stakeholder_id SMALLINT UNSIGNED NOT NULL,
    account_id BIGINT(20) UNSIGNED NOT NULL,
    PRIMARY KEY (stakeholder_id, account_id),
    UNIQUE (account_id),
    CONSTRAINT fk_chargeback_stakeholder_id
    FOREIGN KEY (stakeholder_id)
    REFERENCES stakeholder (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_chargeback_account_id
    FOREIGN KEY (account_id)
    REFERENCES account (id)
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
