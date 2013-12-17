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
        a.name                   AS account_name,
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
    ORDER BY bh.history_date
;
