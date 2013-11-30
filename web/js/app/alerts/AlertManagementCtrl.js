(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertManagementCtrl', [
        '$scope', 'alertApi', 'AccountService', 'serviceApi', '_',
        function($scope, alertApi, accountApi, serviceApi, _) {

            alertApi.data.then(function(data) {

                $scope.alerts = data.alerts;
            });

            accountApi.data.then(function(data) {

                $scope.accounts = [
                    {
                        id   : null,
                        name : 'All Accounts'
                    }
                ].concat(data.accounts);
            });

            serviceApi.data.then(function(data) {

                $scope.pivotTypes = [
                    {
                        id   : null,
                        name : 'None'
                    }
                ].concat(data.pivots);
                $scope.comparisonTypes = data.comparisonTypes;
                $scope.calculationTypes = data.calculationTypes;
                $scope.timeFrames = data.timeFrames;
                $scope.valueTypes = data.valueTypes;
            });

            $scope.remove = function(alert) {

                alertApi.remove(alert).then(function(success) {

                    $scope.alerts = _.without($scope.alerts, alert);
                });
            };
        }]);

})(window.angular);
