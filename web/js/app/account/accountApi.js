(function(ng, undefined) {
    'use strict';

    ng.module('app.account').factory('accountApi', function($http, $q) {

        return {
            create : function() {

                return {
                    id          : 0,
                    external_id : '',
                    name        : '',
                    aws_key     : '',
                    secret_key  : ''
                };
            },
            save   : function(account, master) {

                return $http.post('/account/edit', {
                    account : account,
                    master  : master
                }).then(
                    //Success
                    function(response) {

                        return response.data;
                    });
            },
            saveNew : function(account, master) {

                return $http.post('/account/add', {
                    account : account,
                    master  : master
                }).then(
                    function(response) {

                        return response.data;
                    });
            },
            remove : function(account) {

                return $http.post('account/delete', account)
                    .then(
                    function(response) {

                        return response.data;
                    });
            },
            getAll : function() {

                var deferred = $q.defer();

                $http.get('/getAccounts')
                    .then(
                    function(response) {

                        deferred.resolve(response.data);
                    });

                return deferred.promise;
            }
        };
    });

})(window.angular);
