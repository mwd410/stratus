################################################################################
############################# INDEPENDENT VIEWS ################################
################################################################################

SOURCE db/views/alert_v.sql;

SOURCE db/views/service_provider_v.sql;

SOURCE db/views/service_provider_product_v.sql;

SOURCE db/views/service_type_v.sql;

SOURCE db/views/service_type_category_v.sql;

SOURCE db/views/chargeback_v.sql;


################################################################################
############################## DEPENDENT VIEWS #################################
################################################################################

# depends on
#   - service_type_v
#   - service_type_category_v
#   - service_provider_v
#   - service_provider_product_v
SOURCE db/views/billing_history_v.sql;

# depends on
#   - service_provider_v
#   - service_provider_product_v
SOURCE db/views/service_provider_menu_v.sql;

# depends on
#   - service_provider_v
#   - service_provider_product_v
SOURCE db/views/service_provider_projection_v.sql;

# depends on
#   - service_type_v
#   - service_type_category_v
SOURCE db/views/service_type_menu_v.sql;

# depends on
#   - service_type_v
#   - service_type_category_v
SOURCE db/views/service_type_projection_v.sql;

################################################################################
########################### DOUBLY DEPENDENT VIEWS #############################
################################################################################

# depends on
#   - billing_history_v
#   - service_provider_v
#   - service_provider_product_v
#   - service_type_v
#   - service_type_category_v
SOURCE db/views/alert_push_v.sql;
