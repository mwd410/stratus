'use strict';

app.controller('AccountController', function($scope, $http) {

    $scope.fv = {
        aws_key    : {
            length : 20
        },
        secret_key : {
            length : 40
        }
    };
    $scope.accounts = [];
    $scope.editing = {};
    $scope.deleting = {};
    $scope.adds = [];
    var accountMap = {};
    $http.get('/getAccounts').success(function(response) {

        $scope.accounts = response.accounts;

        for (var i = 0; i < $scope.accounts.length; ++i) {
            var account = $scope.accounts[i];

            accountMap[account.id] = account;
        }

        $scope.masterAccount = response.masterAccount || {
            account_id     : '0',
            billing_bucket : ''
        };
        $scope.originalMasterAccount = angular.copy($scope.masterAccount);
    });

    $scope.isModifying = function(account) {

        return $scope.isAdding(account) || $scope.isEditing(account) || $scope.isDeleting(account);
    };

    $scope.isAdding = function(account) {
        return $scope.adds.indexOf(account) != -1;
    };

    $scope.isEditing = function(account) {
        return $scope.editing[account.id];
    };

    $scope.isDeleting = function(account) {
        return $scope.deleting[account.id];
    };

    $scope.add = function() {
        var account = {
            name       : '',
            aws_key    : '',
            secret_key : ''
        };
        $scope.accounts.unshift(account);
        $scope.adds.push(account);
    };

    $scope.edit = function(account) {
        $scope.editing[account.id] = Utils.apply({}, account);
    };

    $scope.delete = function(account) {
        $scope.deleting[account.id] = Utils.apply({}, account);
    };

    function getAction(account) {

        var action;
        if ($scope.isAdding(account)) {

            action = 'add';

        } else if ($scope.isEditing(account)) {

            action = 'edit';

        } else if ($scope.isDeleting(account)) {

            action = 'delete';

        } else {
            throw new Error('no add, edit or delete state for ' + account.name);
        }
        return action;
    }

    var commitActions = {
            add    : function(account) {
                var params = {
                    master  : $scope.masterAccount,
                    account : account
                };
                $http.post('/account/add', params)
                    .success(function(response) {
                        if (response.success) {

                            $scope.adds.splice($scope.adds.indexOf(account), 1);
                            Utils.apply(account, response.data);
                            console.log(account);
                        }
                    });
            },
            edit   : function(account) {
                var params = {
                    master  : $scope.masterAccount,
                    account : account
                };
                $http.post('/account/edit', params)
                    .success(function(response) {
                        if (response.success) {

                            delete $scope.editing[account.id];
                        }
                    });
            },
            delete : function(account) {
                $http.post('/account/delete', account)
                    .success(function(response) {
                        if (response.success) {
                            $scope.accounts.splice($scope.accounts.indexOf(account), 1);
                            delete $scope.deleting[account.id];
                        }
                    });
            }
        },
        cancelActions = {
            add    : function(account) {
                $scope.accounts.splice($scope.accounts.indexOf(account), 1);
                $scope.adds.splice($scope.adds.indexOf(account), 1);
            },
            edit   : function(account) {
                Utils.apply(account, $scope.editing[account.id]);
                delete $scope.editing[account.id];
            },
            delete : function(account) {
                delete $scope.deleting[account.id];
            }
        };

    $scope.commit = function(account) {

        var action = getAction(account);

        if (commitActions[action]) {
            commitActions[action](account);
            console.log('committed ' + action + ' for ' + account.name);
        }
    };

    $scope.cancel = function(account) {

        var action = getAction(account);

        if (cancelActions[action]) {
            cancelActions[action](account);
            console.log('canceled ' + action + ' for ' + account.name);
        }
        $scope.masterAccount = angular.copy($scope.originalMasterAccount);
    };

    $scope.changeAws = function(error) {
        console.log(error);
    };

    $scope.setMasterAccount = function(account, isMaster) {

        $scope.masterAccount.account_id = isMaster ? account.id : null;
        console.log(arguments);
    };
});
