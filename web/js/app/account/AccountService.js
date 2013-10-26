(function(ng) {
    'use strict';

    ng.module('app.account').service('AccountService', function($http) {

        var serverData,
            master,
            allAccounts = [];

        $http.get('/getAccounts').success(function(response) {

            serverData = response;

            var copy = ng.copy(serverData);
            master = copy.masterAccount;
            service.all = copy.accounts;
        });

        var service = {
            all : allAccounts
        };

        return service;
    });

})(window.angular);