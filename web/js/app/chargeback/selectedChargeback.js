(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').filter('selectedChargeback', function(_) {
    
        return function(map, id) {
        
            var filtered = {};
            _.each(map, function(unit, key) {

                if (unit.stakeholder_id == id) {
                    filtered[key] = unit;
                }
            });

            return filtered;
        };
    });

})(window.angular);
