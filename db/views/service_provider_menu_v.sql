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
            ON spp.id = spsm.service_product_id
;
