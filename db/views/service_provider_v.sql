CREATE OR REPLACE VIEW service_provider_v AS
    SELECT
        sp.id                         AS id,
        ifnull(tsp.tag_name, sp.name) AS name,
        sp.name                       AS original_name
    FROM service_provider sp
        LEFT JOIN tag_service_provider tsp
            ON tsp.name = sp.name
    ORDER BY sp.id
;
