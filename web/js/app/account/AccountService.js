(function(ng) {
    'use strict';

    ng.module('app.account').service('AccountService', function($http, $q) {

        var masterDeferred = $q.defer(),
            service = {
                master : masterDeferred.promise,
                all : $http.get('/getAccounts').then(function(response) {

                    masterDeferred.resolve(response.data.masterAccount);
                    return response.data.accounts;
                })
            };

        return service;
    });

})(window.angular);
