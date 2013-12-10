################################################################################
#
#   Release Notes:
#
################################################################################
######################## SCHEMA CHANGES & DATA MIGRATION #######################
################################################################################

DROP TABLE IF EXISTS alert_classification_type
;

CREATE TABLE IF NOT EXISTS pivot_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    api_name VARCHAR(255)
)
    ENGINE =InnoDB
;

DROP TABLE IF EXISTS alert_comparison_type
;

CREATE TABLE IF NOT EXISTS comparison_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

DROP TABLE IF EXISTS alert_calculation_type
;

CREATE TABLE IF NOT EXISTS calculation_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

DROP TABLE IF EXISTS alert_time_frame
;

CREATE TABLE IF NOT EXISTS time_frame (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

DROP TABLE IF EXISTS alert_value_type
;

CREATE TABLE IF NOT EXISTS value_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

DROP TABLE IF EXISTS alert_object_type
;

DROP TABLE IF EXISTS alert
;

CREATE TABLE IF NOT EXISTS alert (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    pivot_type_id BIGINT(20) UNSIGNED NOT NULL,
    account_id BIGINT(20) UNSIGNED,
    service_provider_id BIGINT(20) UNSIGNED,
    service_provider_product_id BIGINT(20) UNSIGNED,
    service_type_id BIGINT(20) UNSIGNED,
    service_type_category_id BIGINT(20) UNSIGNED,
    comparison_type_id BIGINT(20) UNSIGNED NOT NULL,
    calculation_type_id BIGINT(20) UNSIGNED NOT NULL,
    time_frame_id BIGINT(20) UNSIGNED NOT NULL,
    value_type_id BIGINT(20) UNSIGNED NOT NULL,
    threshold FLOAT UNSIGNED NOT NULL,
    in_email TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    in_breakdown TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    email VARCHAR(60) NOT NULL,
    CONSTRAINT fk_alert_user_id
    FOREIGN KEY (user_id)
    REFERENCES user (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_alert_pivot_type_id
    FOREIGN KEY (pivot_type_id)
    REFERENCES pivot_type (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_alert_account_id
    FOREIGN KEY (account_id)
    REFERENCES account (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_alert_service_provider_id
    FOREIGN KEY (service_provider_id)
    REFERENCES service_provider (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_alert_service_provider_product_id
    FOREIGN KEY (service_provider_product_id)
    REFERENCES service_product (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_alert_service_type_id
    FOREIGN KEY (service_type_id)
    REFERENCES service_type (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_alert_service_type_category_id
    FOREIGN KEY (service_type_category_id)
    REFERENCES service_type_category (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_alert_comparison_type_id
    FOREIGN KEY (comparison_type_id)
    REFERENCES comparison_type (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_alert_calculation_type_id
    FOREIGN KEY (calculation_type_id)
    REFERENCES calculation_type (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_alert_time_frame_id
    FOREIGN KEY (time_frame_id)
    REFERENCES time_frame (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_alert_value_type_id
    FOREIGN KEY (value_type_id)
    REFERENCES value_type (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
    ENGINE =InnoDB
;

################################################################################
############################ DATA INSERTS & CHANGES ############################
################################################################################

REPLACE INTO pivot_type VALUES
(1, 'Service Provider', 'provider'),
(2, 'Service Type', 'type')
;

REPLACE INTO comparison_type VALUES
(1, 'greater than'),
(2, 'less than')
;

REPLACE INTO calculation_type VALUES
(1, 'average'),
(2, 'total')
;

REPLACE INTO time_frame VALUES
(1, 'daily'),
(2, '7 day'),
(3, '30 day')
;

REPLACE INTO value_type VALUES
(1, 'cost'),
(2, 'running hours')
;

################################################################################
################################# VIEW CHANGES #################################
################################################################################

SOURCE db/views.sql;


SELECT
    sum(cost) / count(DISTINCT history_date) AS average,
    history_date
FROM billing_history_v
GROUP BY customer_id
;

REPLACE INTO alert (
    id,
    user_id,
    name,
    pivot_type_id,
    account_id,
    service_provider_id,
    service_provider_product_id,
    service_type_id,
    service_type_category_id,
    comparison_type_id,
    calculation_type_id,
    time_frame_id,
    value_type_id,
    threshold
) VALUES
# + id
# |  + user_id
# |  |                + pivot_type_id
# |  |                |     + account_id
# |  |                |     |     + service_provider_id
# |  |                |     |     |     + service_provider_product_id
# |  |                |     |     |     |     + service_type_id
# |  |                |     |     |     |     |     + service_type_category_id
# |  |                |     |     |     |     |     |  + comparison_type_id
# |  |                |     |     |     |     |     |  |  + calculation_type_id
# |  |                |     |     |     |     |     |  |  |  + time_frame_id
# |  |                |     |     |     |     |     |  |  |  |  + value_type_id
# |  |                |     |     |     |     |     |  |  |  |  |     + threshold
(1, 1, 'my alert 1', 1, null,    1, null, null, null, 1, 2, 3, 1, 120),
(2, 1, 'my alert 2', 2, null, null, null,    1,    1, 2, 1, 3, 2, 1000),
(3, 1, 'my alert 3', 1, null,    1,    1, null, null, 1, 2, 2, 1, 1000),
(4, 1, 'my alert 4', 2,   72, null, null, null, null, 2, 2, 2, 1, 1000)
;

/*

#DAILY:
SELECT
    sum(bhv.cost)
FROM billing_history_v bhv
WHERE u.customer_id
bhv.history_date < curdate()
AND bhv.history_date > now() - INTERVAL 1 DAY
;
*/
