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

    var master = angular.copy($scope.account),
        isNew = $scope.account.id == $scope.newAccountId,
        isModified = false,
        isPendingDelete = false;

    function save() {

        angular.copy($scope.account, master);
        $scope.commitMaster();
    }

    $scope.isAdding = function() {
        return isNew;
    };

    $scope.isEditing = function() {
        return isModified;
    };

    $scope.isDeleting = function() {
        return isPendingDelete;
    };

    $scope.isModifying = function() {
        return $scope.isAdding() ||
            $scope.isEditing() ||
            $scope.isDeleting();
    };

    $scope.edit = function() {
        isModified = true;
    };

    $scope.delete = function() {
        isPendingDelete = true;
    };

    function commitNew() {

        $http.post(
            '/account/add',
            {
                account : $scope.account,
                master  : $scope.masterAccount
            }
        ).success(
            function(result) {

                if (result.success === true) {
                    isNew = false;
                    save();
                }
            }
        );
    }

    function commitModified() {

        $http.post(
            '/account/edit',
            {
                account : $scope.account,
                master  : $scope.masterAccount
            }
        ).success(
            function(result) {

                if (result.success === true) {
                    isModified = false;
                    save();
                }
            }
        );
    }

    function commitPendingDelete() {

        $http.post(
            '/account/delete',
            {id : $scope.account.id}
        ).success(
            function(result) {

                if (result.success === true) {
                    $scope.accounts.splice($scope.account, 1);
                }
            }
        );
    }

    $scope.commit = function() {

        if (isNew) {
            commitNew();
        } else if (isModified) {
            commitModified();
        } else if (isPendingDelete) {
            commitPendingDelete();
        }
    };

    $scope.cancel = function() {

        if (isNew) {
            $scope.accounts.splice($scope.account, 1);
        } else {
            angular.copy(master, $scope.account);
        }
        isNew = false;
        isModified = false;
        isPendingDelete = false;
    };
});
