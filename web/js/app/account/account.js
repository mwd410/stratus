(function(ng, undefined) {
    'use strict';

    ng.module('app.account').factory('account', function($http) {

        return {
            save : function(account) {

                return $http.post('/account/save', account)
                    .then(
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
            }
        };
    });

})(window.angular);