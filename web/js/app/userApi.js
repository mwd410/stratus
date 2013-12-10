(function(ng, undefined) {
    'use strict';

    ng.module('app').factory('user', function($http) {

        var factory =  {};

        $http.get('/user/info').then(function(response) {

            factory.data = response.data.data;
        });

        return factory;
    });

})(window.angular);
