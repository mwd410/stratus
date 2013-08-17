'use strict';

app.controller('AccountManagementController', function($scope, $http) {

    $scope.accounts = [];

    $scope.newAccountId = '';

    $http.get('/getAccounts').success(function(response) {

        $scope.accounts = response.accounts;

        $scope.masterAccount = response.masterAccount;

        $scope.commitMaster();
    });

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

});