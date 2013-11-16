CREATE OR REPLACE VIEW alert_push_v AS
    SELECT
        a.id,
        a.user_id,
        a.threshold,
        sum(bhv.cost) / count(DISTINCT bhv.history_date) AS average,
        concat(
            'Your ',
            (CASE a.alert_classification_type_id
             WHEN 1 THEN concat(spv.name, ' ',
                                ifnull(concat(sppv.name, ' '), ''),
                                #alert object type
                                aot.name, ' ')
             WHEN 2 THEN concat(stv.name, ' ',
                                ifnull(concat(stcv.name, ' '), ''),
                                aot.name, ' ')
             WHEN 3 THEN concat(act.name, ' "',
                                aes_decrypt(ac.name, 'h8zukuqeteSw'), '" ')
             ELSE ''
             END),
            if(a.alert_classification_type_id = 3,
               #account is at / has (exceed / dropped below)
               if(a.alert_comparison_type_id = 3,
                  'is ',
                  'has '),
               #accounts/instances/volumes are at / have (exceeded / dropped below)
               if(a.alert_comparison_type_id = 3,
                  'are ',
                  'have ')),
            #alert_comparision_type
            compt.name, ' a ',
            #alert time frame
            atf.name, ' ',
            #alert calculation type
            calct.name, ' ',
            #alert value type
            avt.name, ' of ',
            if(a.alert_value_type_id = 1,
               concat('$', convert(format(a.threshold, 2) USING utf8)),
               a.threshold),
            '. (actual: ',
            '$',
            format(sum(bhv.cost), 2),
            ')'
        ) AS description
    FROM alert a
        JOIN user u
            ON u.id = a.user_id
        JOIN billing_history_v bhv
            ON bhv.customer_id = u.customer_id
        JOIN alert_classification_type act
            ON act.id = a.alert_classification_type_id
        JOIN alert_comparison_type compt
            ON compt.id = a.alert_comparison_type_id
        JOIN alert_calculation_type calct
            ON calct.id = a.alert_calculation_type_id
        JOIN alert_time_frame atf
            ON atf.id = a.alert_time_frame_id
        JOIN alert_value_type avt
            ON avt.id = a.alert_value_type_id
        LEFT JOIN account ac
            ON ac.id = a.account_id
        LEFT JOIN service_provider_v spv
            ON spv.id = a.service_provider_id
        LEFT JOIN service_provider_product_v sppv
            ON sppv.id = a.service_provider_product_id
        LEFT JOIN service_type_v stv
            ON stv.id = a.service_type_id
        LEFT JOIN service_type_category_v stcv
            ON stcv.id = a.service_type_category_id
        LEFT JOIN alert_object_type aot
            ON aot.id = a.alert_object_type_id
    WHERE bhv.history_date < curdate()
          AND bhv.history_date >=
              date_sub(
                  curdate(), INTERVAL
                  (CASE a.alert_time_frame_id
                   WHEN 1 THEN 1
                   WHEN 2 THEN 7
                   WHEN 3 THEN 30
                   END) DAY)
    GROUP BY a.id
    HAVING sum(bhv.cost) > threshold
    ORDER BY a.id
;
