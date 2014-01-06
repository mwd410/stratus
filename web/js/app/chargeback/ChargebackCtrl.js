(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').controller('ChargebackCtrl', function($scope, chargeback) {

        $scope.chargeback = chargeback;

        $scope.newStakeholderData = {
            name  : '',
            title : '',
            email : ''
        };

        $scope.toolbar = [
            {
                el      : '<button class="st-button brand" ' +
                    'ng-click="onClick()"><i class="icon-plus"></i>' +
                    '<span>Add Stakeholder</span>' +
                    '</button>',
                scope   : {
                    onClick : function() {

                        chargeback.createStakeholder($scope.newStakeholderData);
                    }
                }
            }
        ];
    });

})(window.angular);
