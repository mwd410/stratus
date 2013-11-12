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

ALTER TABLE billing_history
ADD CONSTRAINT fk_billing_history_product_name
FOREIGN KEY (product_name)
REFERENCES service_product (name)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE service_product
CHANGE service_type_id service_type_id BIGINT(20) UNSIGNED,
ADD CONSTRAINT fk_service_product_service_type1
FOREIGN KEY (service_type_id)
REFERENCES service_type (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

alter table billing_history
    add column service_provider_id bigint (20) unsigned,
    add column service_type_id bigint (20) unsigned;

update billing_history bh
    join service_product spp
        on spp.id = bh.product_id
    join service_type_category stc
        on stc.id = bh.service_type_category_id
set bh.service_provider_id = spp.service_provider_id,
    bh.service_type_id = stc.service_type_id;

ALTER TABLE billing_history
ADD CONSTRAINT fk_billing_history_service_provider1
FOREIGN KEY (service_provider_id)
REFERENCES service_provider (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
ADD CONSTRAINT fk_billing_history_service_type1
FOREIGN KEY (service_type_id)
REFERENCES service_type (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

alter table billing_history
    add key billing_history_date1 (history_date);

alter table billing_history
    drop key fk_billing_history_account1,
    add key billing_history_account_cost1 (account_id, cost),
    add key billing_history_date_cost (history_date, cost),
    add key billing_history_account_date_cost (account_id, cost, history_date);

alter table billing_history
    add key billing_history_all (account_id, product_id, service_type_category_id, cost, history_date);

ALTER TABLE billing_history
CHANGE product_id product_id BIGINT(20) UNSIGNED NOT NULL
;

alter table billing_history
    add key cost (cost);

ALTER TABLE service_product
DROP KEY name,
ADD UNIQUE KEY (service_provider_id, name)
;

ALTER TABLE service_product
ADD KEY (id, service_provider_id, name)
;

ALTER TABLE service_provider
ADD UNIQUE KEY (name)
;

ALTER TABLE service_type_category
DROP KEY name,
ADD UNIQUE KEY (service_type_id, name)
;

ALTER TABLE service_provider
ADD COLUMN tag_name VARCHAR(40)
AFTER name
;

ALTER TABLE service_product
ADD COLUMN tag_name VARCHAR(40)
AFTER name
;

ALTER TABLE service_type
ADD COLUMN tag_name VARCHAR(40)
AFTER name
;

ALTER TABLE service_type_category
ADD COLUMN tag_name VARCHAR(40)
AFTER name
;

UPDATE service_provider sp
    JOIN tag_service_provider tsp
        ON tsp.name = sp.name
SET sp.tag_name = tsp.tag_name
;

UPDATE service_product sp
    JOIN tag_service_provider_product tspp
        ON tspp.name = sp.name
SET sp.tag_name = tspp.tag_name
;

UPDATE service_type st
    JOIN tag_service_type tst
        ON tst.name = st.name
SET st.tag_name = tst.tag_name
;

UPDATE service_type_category stc
    JOIN tag_service_type_category tstc
        ON tstc.name = stc.name
SET stc.tag_name = tstc.tag_name
;

################################################################################
############################ DATA INSERTS & CHANGES ############################
################################################################################

REPLACE INTO alert_classification_type VALUES
(1, 'Service Provider'),
(2, 'Service Type')
;

REPLACE INTO alert_object_type VALUES
(1, 'instances'),
(2, 'buckets'),
(3, 'volumes'),
(4, 'accounts')
;

REPLACE INTO alert_comparison_type VALUES
(1, 'greater than'),
(2, 'less than')
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
