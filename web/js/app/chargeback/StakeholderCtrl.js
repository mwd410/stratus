(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('StakeholderCtrl', function($scope, chargeback) {

        $scope.assign = function(unit) {
            chargeback.assign(unit, $scope.stakeholder);
        };

        $scope.unassign = function(unit) {
            chargeback.unassign(unit, $scope.stakeholder);
        };

        $scope.showAssigned = function() {

            return chargeback.showAssigned;
        };

        $scope.toggleShowAssigned = function() {

            chargeback.showAssigned = !chargeback.showAssigned;
        };
    });

})(window.angular);
