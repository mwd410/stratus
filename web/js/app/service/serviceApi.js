(function(ng, undefined) {
    'use strict';

    ng.module('app.service').factory('serviceApi', function($http) {

        var service = {
            data : $http.get('/service').then(function(response) {

                return response.data.data;
            })
        };

        return service;
    });

})(window.angular);
