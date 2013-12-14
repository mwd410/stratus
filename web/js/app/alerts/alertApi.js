(function(ng, undefined) {
    'use strict';

    ng.module('app.alerts').factory('alertApi', function($http, pivotApi, _) {

        var original,
            saveOriginal = function(alert) {

                var originalAlert = _.find(original.alerts, {id : alert.id});

                if (originalAlert) {
                    ng.copy(alert, originalAlert);
                } else {
                    original.alerts.push(alert);
                }
            },
            api = {
            data   : $http.get('/alert/index').then(function(response) {

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

                return $http.post('/alert/delete', params).then(function removePromise(response) {

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

                var url;
                if (alert._new === true) {
                    url = '/alert/create';
                } else {
                    url = '/alert/update';
                }
                return $http.post(url, alert).then(function submitPromise(response) {

                    saveOriginal(response.data.data);

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
