(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').factory('chargeback', function($http, $q) {

        var indexPromise = $http.get('/chargeback/index').then(function(response, _) {

                chargeback.stakeholders = {
                    widgetRows : response.data.data.stakeholders.map(function(stakeholder) {

                        return {
                            widgetColumns : [
                                {
                                    flex    : 1,
                                    widgets : [
                                        {
                                            flex         : 1,
                                            title        : stakeholder.name
                                                + ' <' + stakeholder.email + '>',
                                            stakeholder  : stakeholder,
                                            dynamicTitle : false,
                                            templateFile : 'stakeholder.html'
                                        }
                                    ]
                                }
                            ]
                        };
                    })
                };

                return response.data.data;
            }),
            infoPromise = $http.get('/chargeback/info').then(function(response) {

                chargeback.map = response.data.data.accountProducts.reduce(function(map, pa) {

                    var p = pa.service_provider_product_id,
                        a = pa.account_id,
                        key = [p, a].join('-');

                    map[key] = pa;
                    return map;
                }, {});

                return response.data.data;
            }),
            chargeback = {
                title        : 'Chargeback Assignment',
                indexPromise : indexPromise,
                infoPromise  : infoPromise,
                promise      : $q.all({
                    index : indexPromise,
                    info  : infoPromise
                }).then(
                    function(result) {

                        result.index.chargeback.forEach(function(assignment) {

                            var product = assignment.service_provider_product_id,
                                account = assignment.account_id,
                                key = [product, account].join('-');

                            chargeback.map[key].stakeholder_id = assignment.stakeholder_id;
                        });
                    }
                ),
                units        : {},
                getData      : function(widget) {

                    return widget.stakeholder;
                }
            };


        return chargeback;
    });

})(window.angular);
