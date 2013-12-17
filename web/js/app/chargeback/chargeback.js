(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').factory('chargeback', function($http, $q) {

        var indexPromise = $http.get('/chargeback/index').then(function(response, _) {

                var data = response.data.data;
                chargeback.stakeholderMap = {};
                chargeback.stakeholders = {
                    widgetRows : data.stakeholders.map(function(stakeholder) {

                        stakeholder.units = {};
                        chargeback.stakeholderMap[stakeholder.id] = stakeholder;

                        return {
                            widgetColumns : [
                                {
                                    flex    : 1,
                                    widgets : [
                                        {
                                            flex         : 1,
                                            title        : function() {

                                                return stakeholder.name
                                                    + ' (' + Object.keys(stakeholder.units).length
                                                    + ' Accounts)';
                                            },
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

                chargeback.map = data.accounts.reduce(function(result, account) {

                    account.getKey = function() {

                        return account.id;
                    };
                    result[account.getKey()] = account;
                    return result;
                }, {});

                data.chargeback.forEach(function(unit) {

                    chargeback.map[unit.account_id].stakeholder = chargeback.stakeholderMap[unit.stakeholder_id];
                });

                return response.data.data;
            }),
            chargeback = {
                title        : 'Chargeback Assignment',
                indexPromise : indexPromise,
                showAssigned : false,
                getData      : function(widget) {

                    return widget.stakeholder;
                }
            };


        return chargeback;
    });

})(window.angular);
