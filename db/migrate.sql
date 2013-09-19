SET foreign_key_checks = 0;

# ACCOUNT

ALTER TABLE account
DROP PRIMARY KEY,
DROP KEY customer_id,
DROP FOREIGN KEY account_ibfk_1,
ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
#ADD COLUMN external_id VARCHAR(40) NOT NULL,
CHANGE account_id external_id VARCHAR(40) NOT NULL,
CHANGE account_name name VARCHAR(40) NOT NULL,
CHANGE aws_key provider_key VARCHAR(40) NOT NULL,
ADD PRIMARY KEY (id),
ADD UNIQUE KEY (account_id),
ADD UNIQUE KEY (customer_id, name),
ADD CONSTRAINT fk_account_customer1
FOREIGN KEY (customer_id)
REFERENCES customer (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

# BILLING_INFO_DETAILS
ALTER TABLE billing_file_details
DROP PRIMARY KEY,
DROP FOREIGN KEY billing_file_details_ibfk_1,
ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (id),
ADD UNIQUE KEY (account_id, billing_file),
ADD CONSTRAINT fk_billing_file_details_account1
FOREIGN KEY (account_id)
REFERENCES account (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

UPDATE billing_file_details bfd, account a
SET bfd.account_id = a.id
WHERE bfd.account_id = a.external_id
;

# BILLING_HISTORY
ALTER TABLE billing_history
DROP PRIMARY KEY,
DROP FOREIGN KEY billing_history_ibfk_1,
DROP COLUMN payer_account_id,
ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (id),
ADD UNIQUE KEY (account_id, rate_id, subscription_id, history_date, region, operation),
ADD CONSTRAINT fk_billing_history_account1
FOREIGN KEY (account_id)
REFERENCES account (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

UPDATE billing_history bh, account a
SET bh.account_id = a.id
WHERE bh.account_id = a.external_id;

ALTER TABLE billing_info
DROP PRIMARY KEY,
DROP FOREIGN KEY billing_info_ibfk_1,
DROP COLUMN payer_account_id,
ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (id),
ADD UNIQUE KEY (account_id, rate_id, subscription_id, start_date, end_date, region, operation),
ADD CONSTRAINT fk_billing_info_account1
FOREIGN KEY (account_id)
REFERENCES account (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

ALTER TABLE billing_reservations
DROP PRIMARY KEY,
DROP FOREIGN KEY billing_reservations_ibfk_1,
DROP COLUMN payer_account_id,
ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (id),
ADD UNIQUE KEY (account_id, rate_id),
ADD CONSTRAINT fk_billing_reservations_account1
FOREIGN KEY (account_id)
REFERENCES account (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

ALTER TABLE billing_totals
DROP PRIMARY KEY,
DROP FOREIGN KEY billing_totals_ibfk_1,
DROP COLUMN master_account_id,
ADD COLUMN id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (id),
ADD UNIQUE KEY (account_id, record_type, bill_month),
ADD CONSTRAINT fk_billing_totals_account1
FOREIGN KEY (account_id)
REFERENCES account (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
;

ALTER TABLE customer
CHANGE id id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
ADD UNIQUE INDEX (name)
;

CREATE TABLE IF NOT EXISTS instance (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT
);
