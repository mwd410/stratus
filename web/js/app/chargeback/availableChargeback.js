(function(ng, undefined) {
    'use strict';
    
    ng.module('app.chargeback').filter('availableChargeback', function(_) {
    
        return function(map, showAssigned, id) {

            var filtered = {};
            _.each(map, function(unit, key) {

                if (!unit.stakeholder || showAssigned && unit.stakeholder.id != id) {
                    filtered[key] = unit;
                }
            });

            return filtered;
        };
    });
    
})(window.angular);
