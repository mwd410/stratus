CREATE OR REPLACE VIEW service_provider_v AS
    SELECT
        sp.id                         AS id,
        ifnull(tsp.tag_name, sp.name) AS name,
        sp.name                       AS original_name
    FROM service_provider sp
        LEFT JOIN tag_service_provider tsp
            ON tsp.name = sp.name
    ORDER BY sp.id;

CREATE OR REPLACE VIEW service_provider_product_v AS
    SELECT
        sp.id                          AS id,
        ifnull(tspp.tag_name, sp.name) AS name,
        sp.name                        AS original_name,
        sp.service_provider_id         AS service_provider_id,
        sp.service_type_id             AS service_type_id
    FROM service_product sp
        LEFT JOIN tag_service_provider_product tspp
            ON tspp.name = sp.name
    ORDER BY sp.id;

CREATE OR REPLACE VIEW service_type_v AS
    SELECT
        st.id                         AS id,
        ifnull(tst.tag_name, st.name) AS name,
        st.name                       AS original_name
    FROM service_type st
        LEFT JOIN tag_service_type tst
            ON st.name = tst.name
    ORDER BY st.id;

CREATE OR REPLACE VIEW service_type_category_v AS
    SELECT
        stc.id                          AS id,
        ifnull(tstc.tag_name, stc.name) AS name,
        stc.name                        AS original_name,
        stc.service_type_id             AS service_type_id
    FROM service_type_category stc
        LEFT JOIN tag_service_type_category tstc
            ON stc.name = tstc.name
    ORDER BY stc.id;

CREATE OR REPLACE VIEW billing_history_v AS
    SELECT
        bh.id                    AS id,
        bh.description           AS description,
        bh.operation             AS operation,
        bh.usage_type            AS usage_type,
        bh.history_date          AS history_date,
        bh.cost                  AS cost,
        a.customer_id            AS customer_id,
        bh.account_id            AS account_id,
        stcv.id                  AS service_type_category_id,
        stcv.name                AS service_type_category_name,
        stcv.service_type_id     AS service_type_id,
        stv.name                 AS service_type_name,
        sppv.service_provider_id AS service_provider_id,
        spv.name                 AS service_provider_name,
        sppv.id                  AS service_product_id,
        sppv.name                AS service_provider_product_name
    FROM billing_history bh
        JOIN service_type_category_v stcv
            ON stcv.id = bh.service_type_category_id
        JOIN service_provider_product_v sppv
            ON sppv.original_name = bh.product_name
        JOIN service_provider_v spv
            ON spv.id = sppv.service_provider_id
        JOIN service_type_v stv
            ON stv.id = stcv.service_type_id
        JOIN account a
            ON a.id = bh.account_id
    ORDER BY bh.history_date;

CREATE OR REPLACE VIEW service_provider_menu_v AS
    SELECT
        spsm.customer_id AS customer_id,
        sp.id            AS type_id,
        sp.name          AS type_name,
        spp.id           AS sub_type_id,
        spp.name         AS sub_type_name
    FROM service_provider_menu spm
        JOIN service_provider_sub_menu spsm
            ON spsm.service_provider_menu_id = spm.id
        JOIN service_provider_v sp
            ON sp.id = spm.service_provider_id
        JOIN service_provider_product_v spp
            ON spp.id = spsm.service_product_id;

CREATE OR REPLACE VIEW service_type_menu_v AS
    SELECT
        stsm.customer_id AS customer_id,
        stv.id           AS type_id,
        stv.name         AS type_name,
        stcv.id          AS sub_type_id,
        stcv.name        AS sub_type_name
    FROM service_type_menu stm
        JOIN service_type_sub_menu stsm
            ON stsm.service_type_menu_id = stm.id
        JOIN service_type_v stv
            ON stv.id = stm.service_type_id
        JOIN service_type_category_v stcv
            ON stcv.id = stsm.service_type_category_id;

CREATE OR REPLACE VIEW service_provider_projection_v AS
    SELECT
        a.customer_id    AS customer_id,
        a.id             AS account_id,
        spv.id           AS type_id,
        spv.name         AS type_name,
        sppv.id          AS sub_type_id,
        sppv.name        AS sub_type_name,
        spp.slope        AS slope,
        spp.y_intercept  AS y_intercept,
        spp.eom_estimate AS estimate,
        spp.history_date AS history_date
    FROM service_product_projection spp
        JOIN account a
            ON a.id = spp.account_id
        JOIN service_provider_product_v sppv
            ON sppv.id = spp.service_product_id
        JOIN service_provider_v spv
            ON spv.id = sppv.service_provider_id;

CREATE OR REPLACE VIEW service_type_projection_v AS
    SELECT
        a.customer_id    AS customer_id,
        a.id             AS account_id,
        stv.id           AS type_id,
        stv.name         AS type_name,
        stcv.id          AS sub_type_id,
        stcv.name        AS sub_type_name,
        stp.slope        AS slope,
        stp.y_intercept  AS y_intercept,
        stp.eom_estimate AS estimate,
        stp.history_date AS history_date
    FROM service_type_projection stp
        JOIN account a
            ON a.id = stp.account_id
        JOIN service_type_category_v stcv
            ON stcv.id = stp.service_type_category_id
        JOIN service_type_v stv
            ON stv.id = stcv.service_type_id;
