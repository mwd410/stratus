CREATE OR REPLACE VIEW service_type_v AS
    SELECT
        st.id                         AS id,
        ifnull(tst.tag_name, st.name) AS name,
        st.name                       AS original_name
    FROM service_type st
        LEFT JOIN tag_service_type tst
            ON st.name = tst.name
    ORDER BY st.id
;
