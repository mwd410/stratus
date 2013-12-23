(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('StakeholderCtrl', function($scope, chargeback) {

        $scope.assign = function(unit) {
            chargeback.assign(unit, $scope.widget.stakeholder);
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
