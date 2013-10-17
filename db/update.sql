################################################################################
#
#   Release Notes:
#
################################################################################
######################## SCHEMA CHANGES & DATA MIGRATION #######################
################################################################################

CREATE TABLE IF NOT EXISTS tag_service_provider (
    name VARCHAR(255) NOT NULL PRIMARY KEY,
    tag_name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS tag_service_provider_product (
    name VARCHAR(255) NOT NULL PRIMARY KEY,
    tag_name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS tag_service_type (
    name VARCHAR(255) NOT NULL PRIMARY KEY,
    tag_name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB;

CREATE TABLE IF NOT EXISTS tag_service_type_category (
    name VARCHAR(255) NOT NULL PRIMARY KEY,
    tag_name VARCHAR(255) NOT NULL
)
    ENGINE =InnoDB;

################################################################################
############################ DATA INSERTS & CHANGES ############################
################################################################################

INSERT INTO tag_service_provider VALUES
('Amazon Web Services', 'Amazon')
ON DUPLICATE KEY UPDATE tag_name = values(tag_name);

INSERT INTO tag_service_provider_product VALUES
('Amazon Elastic Compute Cloud', 'EC2'),
('Amazon Simple Storage Service', 'S3'),
('Amazon Virtual Private Cloud', 'VPC')
ON DUPLICATE KEY UPDATE tag_name = values(tag_name);

################################################################################
################################# VIEW CHANGES #################################
################################################################################

CREATE OR REPLACE VIEW service_provider_menu_v AS
    SELECT
        spsm.customer_id                AS customer_id,
        ifnull(tsp.tag_name, spv.name)  AS service_provider_name,
        spv.id                          AS service_provider_id,
        ifnull(tspp.tag_name, spd.name) AS service_product_name,
        spd.id                          AS service_product_id
    FROM service_provider_menu spm
        JOIN service_provider_sub_menu spsm
            ON spsm.service_provider_menu_id = spm.id
        JOIN service_provider spv
            ON spv.id = spm.service_provider_id
        JOIN service_product spd
            ON spd.id = spsm.service_product_id
        LEFT JOIN tag_service_provider tsp
            ON tsp.name = spv.name
        LEFT JOIN tag_service_provider_product tspp
            ON tspp.name = spd.name
;

CREATE OR REPLACE VIEW service_type_menu_v AS
    SELECT
        stsm.customer_id              AS customer_id,
        ifnull(tst.tag_name, st.name) AS service_type_name,
        st.id                         AS service_type_id,
        stc.name                      AS service_type_category,
        stc.id                        AS service_type_category_id
    FROM service_type_menu stm
        JOIN service_type_sub_menu stsm
            ON stsm.service_type_menu_id = stm.id
        JOIN service_type st
            ON st.id = stm.service_type_id
        JOIN service_type_category stc
            ON stc.id = stsm.service_type_category_id
        LEFT JOIN tag_service_type tst
            ON tst.name = st.name
        LEFT JOIN tag_service_type_category tstc
            ON tstc.name = stc.name
;
