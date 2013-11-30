(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').factory('alertApi', function($http, _) {

        var original, api = {
            data   : $http.get('/alerts').then(function(response) {

                original = ng.copy(response.data);
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
            },
            getOriginal : function(alert) {

                return _.find(original.alerts, {
                    id : alert.id
                });
            }
        };

        return api;
    });

})(window.angular);
