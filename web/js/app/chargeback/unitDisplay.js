(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').filter('unitDisplay', function(chargeback) {

        return function(unit) {

            var result = unit.name + ' (' + unit.service_provider_name + ')';

            if (unit.stakeholder) {
                result += ' - Assigned to ' + unit.stakeholder.name;
            }

            return result;
        };
    });

})(window.angular);
