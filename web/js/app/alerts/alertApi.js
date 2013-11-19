(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').factory('alertApi', function($http) {

        return {
            data : $http.get('/alerts').then(function(response) {

                return response.data;
            })
        };
    });

})(window.angular);
