CREATE OR REPLACE VIEW chargeback_v AS
    SELECT
        c.*,
        a.customer_id
    FROM chargeback c
        JOIN account a
            ON a.id = c.account_id
;
