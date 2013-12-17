(function(ng, undefined) {
    'use strict';
    
    ng.module('app.chargeback').filter('availableChargeback', function(_) {
    
        return function(map) {

            var filtered = {};
            _.each(map, function(unit, key) {

                if (!unit.stakeholder_id) {
                    filtered[key] = unit;
                }
            });

            return filtered;
        };
    });
    
})(window.angular);
