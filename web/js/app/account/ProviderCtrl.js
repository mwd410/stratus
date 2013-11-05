(function(ng, undefined) {
    'use strict';

    ng.module('app.account').controller('ProviderCtrl', function($scope, account) {

        var master;

        $scope.saveMaster = function(masterAccount) {

            master = ng.copy(masterAccount);
            $scope.masterAccount = masterAccount;
        };

        account.getAll().then(function(data) {

            $scope.accounts = data.accounts;
            $scope.saveMaster(data.master);
        });

        $scope.add = function() {

            $scope.accounts.unshift(account.new());
        };

        $scope.resetMaster = function() {

            ng.copy(master, $scope.masterAccount);
        };

        $scope.isMaster = function(account) {

            return master && master.account_id == account.id;
        };
    });

})(window.angular);
