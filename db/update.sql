################################################################################
#
#   Release Notes:
#
################################################################################
######################## SCHEMA CHANGES & DATA MIGRATION #######################
################################################################################

CREATE TABLE IF NOT EXISTS pivot_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    api_name varchar(255)
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS comparison_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS calculation_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS time_frame (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

CREATE TABLE IF NOT EXISTS value_type (
    id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB
;

drop table if exists alert;
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
    threshold FLOAT UNSIGNED NOT NULL
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


select
    sum(cost) / count(distinct history_date) as average,
    history_date
    from billing_history_v
    group by customer_id;

REPLACE INTO alert VALUES
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
( 1, 1, 'my alert 1', 1, null,    1, null, null, null, 1, 2, 3, 1,  120),
( 2, 1, 'my alert 2', 2, null, null, null,    1,    1, 2, 1, 4, 2, 1000),
( 3, 1, 'my alert 3', 1, null,    1,    1, null, null, 3, 2, 2, 1, 1000),
( 4, 1, 'my alert 4', 3,   72, null, null, null, null, 3, 2, 2, 1, 1000)
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
