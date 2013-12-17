(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('StakeholderCtrl', function($scope, chargeback) {

        $scope.assign = function(unit) {

            if (unit.stakeholder) {
                // remove this unit from its current stakeholder
                delete unit.stakeholder.units[unit.getKey()];
            }
            unit.stakeholder = $scope.widget.stakeholder;
            $scope.widget.stakeholder.units[unit.getKey()] = unit;
        };

        $scope.unassign = function(unit) {

            delete unit.stakeholder;
            delete $scope.widget.stakeholder.units[unit.getKey()];
        };

        $scope.showAssigned = function() {

            return chargeback.showAssigned;
        };

        $scope.toggleShowAssigned = function() {

            chargeback.showAssigned = !chargeback.showAssigned;
        };
    });

})(window.angular);
