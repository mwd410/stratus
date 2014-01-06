(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').factory('chargeback', function($http, $q) {

        var initStakeholder = function(stakeholder) {

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
                                            + ' ('
                                            + Object.keys(stakeholder.units).length
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
            },
            indexPromise = $http.get('/chargeback/index').then(function(response, _) {

                var data = response.data.data;
                chargeback.stakeholderMap = {};
                chargeback.stakeholders = {
                    widgetRows : data.stakeholders.map(function(stakeholder) {
                        return initStakeholder(stakeholder);
                    })
                };

                chargeback.unitMap = data.accounts.reduce(function(result, account) {

                    account.getKey = function() {

                        return account.id;
                    };
                    result[account.getKey()] = account;
                    return result;
                }, {});

                data.chargeback.forEach(function(unit) {

                    if (chargeback.unitMap[unit.account_id]) {
                        chargeback.unitMap[unit.account_id].stakeholder = chargeback.stakeholderMap[unit.stakeholder_id];
                        chargeback.stakeholderMap[unit.stakeholder_id].units[unit.account_id] = chargeback.unitMap[unit.account_id];
                    }
                });

                return response.data.data;
            }),
            chargeback = {
                title        : 'Chargeback Assignment',
                indexPromise : indexPromise,
                showAssigned : false,
                getData      : function(widget) {

                    return widget.stakeholder;
                },
                assign       : function(unit, stakeholder) {

                    var oldStakeholder = unit.stakeholder;

                    if (unit.stakeholder) {
                        // remove this unit from its current stakeholder
                        delete unit.stakeholder.units[unit.getKey()];
                    }
                    unit.stakeholder = stakeholder;
                    stakeholder.units[unit.getKey()] = unit;

                    $http.post('/chargeback/assign', {
                        account_id     : unit.id,
                        stakeholder_id : stakeholder.id
                    })
                        .then(function(response) {

                            if (!response.data.success) {
                                throw new Error;
                            }
                        })
                        .catch(function(error) {

                            if (oldStakeholder) {
                                unit.stakeholder = oldStakeholder;
                            } else {
                                delete unit.stakeholder;
                            }
                            delete stakeholder.units[unit.getKey()];
                        });
                },
                unassign     : function(unit, stakeholder) {

                    var oldStakeholder = unit.stakeholder;
                    delete unit.stakeholder;
                    delete stakeholder.units[unit.getKey()];

                    $http.post('/chargeback/unassign', {
                        account_id : unit.id
                    })
                        .then(function(response) {

                            if (!response.data.success) {
                                throw new Error;
                            }
                        })
                        .catch(function(error) {
                            // Revert the unassign
                            unit.stakeholder = oldStakeholder;
                            stakeholder.units[unit.getKey()] = unit;
                        });
                },
                createStakeholder : function(data) {

                    chargeback.stakeholders.widgetRows.unshift(initStakeholder(data));
                }
            };


        return chargeback;
    });

})(window.angular);
