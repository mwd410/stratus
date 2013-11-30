(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').factory('alertApi', function($http, _) {

        var api = {
            data   : $http.get('/alerts').then(function(response) {

                return response.data;
            }),
            remove : function(alert) {

                var params = {
                    id : alert.id
                };

                return $http.post('/alerts/delete', params).then(function(response) {

                    var success = response.data.success;

                    if (success) {
                        api.data = api.data.then(function(data) {

                            _.remove(data.alerts, params);
                            return data;
                        });
                    }

                    return success;
                });
            }
        };

        return api;
    });

})(window.angular);
