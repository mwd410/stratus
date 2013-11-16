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
            ON stcv.id = stsm.service_type_category_id
;
