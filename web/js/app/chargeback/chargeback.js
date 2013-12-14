(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').factory('chargeback', function($http, _) {

        var chargeback = {
            title : 'Chargeback Assignment',
            promise : $http.get('/chargeback/index').then(function(response) {

                chargeback.targets = {
                    widgetRows : response.data.data.map(function(target) {

                        return {
                            widgetColumns : [
                                {
                                    flex    : 1,
                                    widgets : [
                                        {
                                            flex   : 1,
                                            title  : target.name
                                                + ' <' + target.email + '>',
                                            target : target,
                                            dynamicTitle : false
                                        }
                                    ]
                                }
                            ]
                        };
                    })
                };


                return response.data;
            })
        };

        return chargeback;
    });

})(window.angular);
