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
    name VARCHAR(255) NOT NULL,
    alert_calculation_type_id BIGINT(20) UNSIGNED NOT NULL
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

INSERT INTO alert_classification_type VALUES
(1, 'Service Provider'),
(2, 'Service Type'),
(3, 'Account')
ON DUPLICATE KEY UPDATE id=id
;

INSERT INTO alert_object_type VALUES
(1, 'instances'),
(2, 'buckets'),
(3, 'volumes'),
(4, 'accounts')
ON DUPLICATE KEY UPDATE id=id
;

INSERT INTO alert_comparison_type VALUES
(1, 'exceeded'),
(2, 'dropped below'),
(3, 'at')
ON DUPLICATE KEY UPDATE id=id
;

INSERT INTO alert_calculation_type VALUES
(1, 'an average'),
(2, 'a total')
ON DUPLICATE KEY UPDATE id=id
;

INSERT INTO alert_time_frame VALUES
(1, 'daily', 2),
(2, 'weekly', 2),
(3, 'monthly', 2),
(4, '7 day rolling', 1),
(5, '30 day rolling', 1)
ON DUPLICATE KEY UPDATE id=id
;

INSERT INTO alert_value_type VALUES
(1, 'cost'),
(2, 'running hours')
ON DUPLICATE KEY UPDATE id=id
;

################################################################################
################################# VIEW CHANGES #################################
################################################################################

SOURCE db / views.sql
;


CREATE OR REPLACE VIEW alert_view AS
    SELECT
        a.id,
        a.user_id AS user_id,
        concat(
            'Your ',
            (CASE a.alert_classification_type_id
             WHEN 1 THEN concat(spv.name, ' ',
                                ifnull(concat(sppv.name, ' '), ''),
                                #alert object type
                                aot.name, ' ')
             WHEN 2 THEN concat(stv.name, ' ',
                                ifnull(concat(stcv.name, ' '), ''),
                                aot.name, ' ')
             WHEN 3 THEN concat(act.name, ' "',
                                aes_decrypt(ac.name, 'h8zukuqeteSw'), '" ')
             ELSE ''
             END),
            if(a.alert_classification_type_id = 3,
               #account is at / has (exceed / dropped below)
               if(a.alert_comparison_type_id = 3,
                  'is ',
                  'has '),
               #accounts/instances/volumes are at / have (exceeded / dropped below)
               if(a.alert_comparison_type_id = 3,
                  'are ',
                  'have ')),
            #alert_comparision_type
            compt.name, ' ',
            #alert calculation type
            calct.name, ' ',
            #alert time frame
            atf.name, ' ',
            #alert value type
            avt.name, ' of ',
            if(a.alert_value_type_id = 1,
               concat('$', convert(format(a.threshold, 2) USING utf8)),
               a.threshold),
            '.'
        )         AS description
    FROM alert a
        JOIN alert_classification_type act
            ON act.id = a.alert_classification_type_id
        JOIN alert_comparison_type compt
            ON compt.id = a.alert_comparison_type_id
        JOIN alert_calculation_type calct
            ON calct.id = a.alert_calculation_type_id
        JOIN alert_time_frame atf
            ON atf.id = a.alert_time_frame_id
        JOIN alert_value_type avt
            ON avt.id = a.alert_value_type_id
        LEFT JOIN account ac
            ON ac.id = a.account_id
        LEFT JOIN service_provider_v spv
            ON spv.id = a.service_provider_id
        LEFT JOIN service_provider_product_v sppv
            ON sppv.id = a.service_provider_product_id
        LEFT JOIN service_type_v stv
            ON stv.id = a.service_type_id
        LEFT JOIN service_type_category_v stcv
            ON stcv.id = a.service_type_category_id
        LEFT JOIN alert_object_type aot
            ON aot.id = a.alert_object_type_id
    ORDER BY a.id
;

REPLACE INTO alert VALUES
(1, 1, 1, null, 1, 2, null, null, 4, 1, 2, 1, 1, 1000),
(2, 1, 2, null, null, null, 1, 1, 2, 2, 1, 4, 2, 1000),
(3, 1, 1, null, 1, 1, null, null, 3, 3, 2, 2, 1, 1000),
(4, 1, 3, 72, null, null, null, null, null, 3, 2, 2, 1, 1000)
;
