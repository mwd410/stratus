(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').factory('alertApi', function($http, pivotApi, _) {

        var original, api = {
            data   : $http.get('/alerts').then(function(response) {

                original = ng.copy(response.data);
                return response.data;
            }),
            info   : $http.get('/alert/info').then(function(response) {

                return response.data.data;
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
            },
            submit : function(alert) {

                $http.post('/alerts/update',{alert : alert}).then(function(response) {

                    return response.data;
                });
            },
            getProviders : function() {

                return pivotApi.getMajor('provider');
            },
            getProducts : function(alert) {

                return pivotApi.getMinor('provider', alert.service_provider_id);
            },
            getServiceTypes : function() {

                return pivotApi.getMajor('type');
            },
            getServiceCategories : function(alert) {

                return pivotApi.getMinor('type', alert.service_type_id);
            }
        };

        return api;
    });

})(window.angular);
