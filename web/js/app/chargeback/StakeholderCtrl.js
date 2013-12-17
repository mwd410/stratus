(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('StakeholderCtrl', function($scope, chargeback) {

        $scope.assign = function(unit) {

            unit.stakeholder_id = $scope.widget.stakeholder.id;
        };

        $scope.unassign = function(unit) {

            delete unit.stakeholder_id;
        };
    });

})(window.angular);
