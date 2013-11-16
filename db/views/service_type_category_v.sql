CREATE OR REPLACE VIEW service_type_category_v AS
    SELECT
        stc.id                          AS id,
        ifnull(tstc.tag_name, stc.name) AS name,
        stc.name                        AS original_name,
        stc.service_type_id             AS service_type_id
    FROM service_type_category stc
        LEFT JOIN tag_service_type_category tstc
            ON stc.name = tstc.name
    ORDER BY stc.id
;
