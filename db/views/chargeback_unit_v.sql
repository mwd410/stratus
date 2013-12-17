CREATE OR REPLACE VIEW chargeback_unit_v AS
    SELECT
        c.*,
        a.customer_id
    FROM chargeback_unit c
        JOIN account a
            ON a.id = c.account_id
;
