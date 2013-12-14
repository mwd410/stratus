(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').controller('AlertManagementCtrl', [
        '$scope', 'alertApi', 'AccountService', 'pivotApi', '_', 'user',
        function($scope, alertApi, accountApi, pivotApi, _, user) {

            alertApi.data.then(function(data) {

                $scope.alerts = ng.copy(data.alerts);
            });

            accountApi.data.then(function(data) {

                $scope.accounts = [
                    {
                        id   : null,
                        name : 'All Accounts'
                    }
                ].concat(data.accounts);
            });

            pivotApi.promise.then(function(data) {

                $scope.pivotTypes = [
                    {
                        id   : null,
                        name : 'None'
                    }
                ].concat(data.pivotTypes);
            });

            alertApi.info.then(function(data) {

                $scope.comparisonTypes = data.comparisonTypes;
                $scope.calculationTypes = data.calculationTypes;
                $scope.timeFrames = data.timeFrames;
                $scope.valueTypes = data.valueTypes;
            });

            $scope.remove = function(alert) {

                if (alert._new === true) {
                    $scope.alerts = _.without($scope.alerts, alert);
                } else {

                    alertApi.remove(alert).then(function(success) {

                        $scope.alerts = _.without($scope.alerts, alert);
                    });
                }
            };

            $scope.add = function() {

                $scope.alerts.unshift({
                    _new : true
                });
            };

            $scope.$watch(function() {return user.data && user.data.email;}, function(email) {

                $scope.userEmail = email;
            });
        }]);

})(window.angular);
