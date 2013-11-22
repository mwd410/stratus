(function(ng) {
    'use strict';

    ng.module('app.account').service('AccountService', function($http, $q) {

        var service = {
                data : $http.get('/getAccounts').then(function(response) {

                    return response.data;
                })
            };

        service.accounts = service.data.then(function(data) {

            return data.accounts;
        });

        service.master = service.data.then(function(data) {
            return data.masterAccount;
        });

        return service;
    });

})(window.angular);
