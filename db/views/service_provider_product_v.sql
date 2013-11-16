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
    ORDER BY sp.id
;
