(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').factory('chargeback', function($http, _) {

        var chargeback = {
            title   : 'Chargeback Assignment',
            promise : $http.get('/chargeback/index').then(function(response) {

                chargeback.targets = {
                    widgetRows : response.data.data.map(function(target) {

                        _.each(target.units, function(unit) {

                            var p = unit.product.id,
                                a = unit.account.id,
                                key = [p,a].join('-');

                            chargeback.units[key] = target;
                        });

                        return {
                            widgetColumns : [
                                {
                                    flex    : 1,
                                    widgets : [
                                        {
                                            flex         : 1,
                                            title        : target.name
                                                + ' <' + target.email + '>',
                                            target       : target,
                                            dynamicTitle : false,
                                            templateFile : 'stakeholder.html',
                                            data         : true
                                        }
                                    ]
                                }
                            ]
                        };
                    })
                };


                return response.data;
            }),
            units : {},
            getData : function(widget) {

                return widget.data;
            }
        };

        return chargeback;
    });

})(window.angular);
