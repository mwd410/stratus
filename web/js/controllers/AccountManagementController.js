(function(ng) {
    'use strict';

    ng.module('App')
        .controller('AccountManagementController', [
            '$scope', '$http', 'AccountService',
            function($scope, $http, AccountService) {

                $scope.accounts = AccountService.all;

                $scope.newAccountId = '';

                $scope.commitMaster = function() {

                    $scope.originalMasterAccount = angular.copy($scope.masterAccount);
                };

                $scope.add = function() {

                    $scope.accounts.unshift(
                        {
                            id         : $scope.newAccountId,
                            name       : '',
                            aws_key    : '',
                            secret_key : ''
                        }
                    );
                };

            }
        ]);

})(window.angular);