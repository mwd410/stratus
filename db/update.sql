################################################################################
#
#   Release Notes:
#
################################################################################
######################## SCHEMA CHANGES & DATA MIGRATION #######################
################################################################################

CREATE TABLE IF NOT EXISTS alert_classification_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS alert_object_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS alert_comparison_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS alert_calculation_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS alert_time_frame (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS alert_value_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS alert (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    alert_classification_type_id BIGINT(20) UNSIGNED NOT NULL,
    account_id BIGINT(20) UNSIGNED,
    service_provider_id BIGINT(20) UNSIGNED,
    service_provider_product_id BIGINT(20) UNSIGNED,
    service_type_id BIGINT(20) UNSIGNED,
    service_type_category_id BIGINT(20) UNSIGNED,
    alert_object_type_id BIGINT(20) UNSIGNED,
    alert_comparison_type_id BIGINT(20) UNSIGNED NOT NULL,
    alert_calculation_type_id BIGINT(20) UNSIGNED NOT NULL,
    alert_time_frame_id BIGINT(20) UNSIGNED NOT NULL,
    alert_value_type_id BIGINT(20) UNSIGNED NOT NULL,
    threshold FLOAT UNSIGNED NOT NULL
)
    ENGINE =InnoDB
;

################################################################################
############################ DATA INSERTS & CHANGES ############################
################################################################################

REPLACE INTO alert_classification_type VALUES
(1, 'Service Provider'),
(2, 'Service Type'),
(3, 'Account')
;

REPLACE INTO alert_object_type VALUES
(1, 'instances'),
(2, 'buckets'),
(3, 'volumes'),
(4, 'accounts')
;

REPLACE INTO alert_comparison_type VALUES
(1, 'exceeded'),
(2, 'dropped below'),
(3, 'at')
;

REPLACE INTO alert_calculation_type VALUES
(1, 'average'),
(2, 'total')
;

REPLACE INTO alert_time_frame VALUES
(1, 'daily'),
(2, '7 day'),
(3, '30 day')
;

REPLACE INTO alert_value_type VALUES
(1, 'cost'),
(2, 'running hours')
;

################################################################################
################################# VIEW CHANGES #################################
################################################################################

SOURCE db/views.sql;

/*

select
    sum(cost) / count(distinct history_date) as average,
    history_date
    from billing_history_v
    group by customer_id;

REPLACE INTO alert VALUES
# + id
# |  + user_id
# |  |  + alert_classification_id
# |  |  |     + account_id
# |  |  |     |     + service_provider_id
# |  |  |     |     |     + service_provider_product_id
# |  |  |     |     |     |     + service_type_id
# |  |  |     |     |     |     |     + service_type_category_id
# |  |  |     |     |     |     |     |     + alert_object_type_id
# |  |  |     |     |     |     |     |     |  + alert_comparison_type_id
# |  |  |     |     |     |     |     |     |  |  + alert_calculation_type_id
# |  |  |     |     |     |     |     |     |  |  |  + alert_time_frame_id
# |  |  |     |     |     |     |     |     |  |  |  |  + alert_value_type_id
# |  |  |     |     |     |     |     |     |  |  |  |  |     + threshold
( 1, 1, 1, null,    1, null, null, null,    4, 1, 2, 3, 1,  120),
( 2, 1, 2, null, null, null,    1,    1,    2, 2, 1, 4, 2, 1000),
( 3, 1, 1, null,    1,    1, null, null,    3, 3, 2, 2, 1, 1000),
( 4, 1, 3,   72, null, null, null, null, null, 3, 2, 2, 1, 1000)
;

#DAILY:
SELECT
    sum(bhv.cost)
FROM billing_history_v bhv
WHERE u.customer_id
bhv.history_date < curdate()
AND bhv.history_date > now() - INTERVAL 1 DAY
;
*/
