(function(ng) {
    'use strict';

    ng.module('app')
        .factory('Provider', function($http) {

            var service = {
                all : []
            };
            $http.get('/providers').success(function(result) {

                service.all = result;
            });

            return service;
        });

})(window.angular);