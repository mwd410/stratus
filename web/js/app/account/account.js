(function(ng, undefined) {
    'use strict';

    ng.module('app.account').factory('account', function($http) {

        return {
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
            remove : function(account) {

                return $http.post('account/delete', account)
                    .then(
                    function(response) {

                        return response.data;
                    });
            },
            getAll : function() {

                return $http.get('/getAccounts')
                    .then(
                    function(response) {

                        return response.data;
                    });
            }
        };
    });

})(window.angular);
