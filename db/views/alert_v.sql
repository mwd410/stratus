CREATE OR REPLACE VIEW alert_v AS
    SELECT
        a.*,
        u.customer_id
    FROM alert a
        JOIN user u
            ON u.id = a.user_id
;
