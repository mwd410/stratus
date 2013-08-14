'use strict';

app.controller('AccountController', function($scope, $http) {

    $scope.fv = {
        aws_key: {
            length : 20
        },
        secret_key:{
            length : 40
        }
    };
    $scope.accounts = [];
    $scope.editing = {};
    $scope.deleting = {};
    $scope.adds = [];
    var accountMap = {};
    $http.get('/getAccounts').success(function(response) {

        for (var i = 0; i < response.length; ++i) {
            var account = response[i];

            account.expanded = false;
            accountMap[account.id] = account;
        }
        $scope.accounts = response;
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
            edit : function(account) {
                $http.post('/account/edit', account)
                    .success(function(response) {
                        if (response.success) {

                            delete $scope.editing[account.id];
                        }
                    });
            },
            add : function(account) {
                $http.post('/account/add', account)
                    .success(function(response) {
                        if (response.success) {

                            $scope.adds.splice($scope.adds.indexOf(account), 1);
                            Utils.apply(account, response.data);
                            console.log(account);
                        }
                    });
            }
        },
        cancelActions = {
            edit : function(account) {
                Utils.apply(account, $scope.editing[account.id]);
                delete $scope.editing[account.id];
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
    };

    $scope.changeAws = function(error) {
        console.log(error);
    }
});
