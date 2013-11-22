(function(ng, undefined) {
    'use strict';

    ng.module('app.account').controller('AccountCtrl', function($scope, accountApi, account) {

        $scope.edit = function() {

            accountApi.edit($scope.account);
        };

        $scope.remove = function() {

            accountApi.remove($scope.account);
        };

        $scope.cancel = function() {

            accountApi.cancel($scope.account);
        };

        $scope.isExpanded = function() {

            return accountApi.isDirty($scope.account);
        };

        $scope.isSaving = function() {

            return accountApi.isSaving($scope.account);
        };

        $scope.commit = function() {

            accountApi.commit($scope.account);
        };
    });

})(window.angular);
