(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').filter('unitDisplay', function() {

        return function(unit) {

            if (unit.accounts) {
                return unit.service_provider_name
                    + ' - '
                    + unit.service_provider_product_name
                    + '(' + unit.accounts.length + 'Accounts)';
            } else {

                return
            }
        };
    });

})(window.angular);
