(function(ng, undefined) {
    'use strict';

    ng.module('app.account').controller('ProviderCtrl', function($scope, accountApi) {

        var master;

        $scope.master = {
            account_id : null
        };

        $scope.saveMaster = function(masterAccount) {

            master = ng.copy(masterAccount);
            $scope.master = masterAccount;
        };

        $scope.setMaster = function(account, isMaster) {

            $scope.master.account_id = isMaster ? account.id : null;
        };

        accountApi.getAll().then(function(data) {

            $scope.accounts = data.accounts;
            $scope.saveMaster(data.masterAccount);
        });

        $scope.add = function() {

            $scope.accounts.push(accountApi.create());
        };

        $scope.resetMaster = function() {

            ng.copy(master, $scope.master);
        };

        $scope.isMaster = function(account) {

            return $scope.master.account_id == account.id;
        };

        $scope.remove = function(account) {

            var index = $scope.accounts.indexOf(account);
            $scope.accounts.splice(index, 1);
        };
    });

})(window.angular);
