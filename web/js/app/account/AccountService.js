(function(ng) {
    'use strict';

    ng.module('app.account').service('AccountService', function($http, $q) {

        var service = {
                data : $http.get('/getAccounts').then(function(response) {

                    return response.data;
                })
            };

        return service;
    });

})(window.angular);
