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
            ON stv.id = stcv.service_type_id
;
