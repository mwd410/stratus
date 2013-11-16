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
            ON spv.id = sppv.service_provider_id
;
