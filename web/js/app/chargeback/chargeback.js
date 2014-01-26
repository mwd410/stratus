(function(ng, undefined) {
    'use strict';

    ng.module('app.chargeback').factory('chargeback', function($http, _) {

        var initStakeholder = function(stakeholder) {

                stakeholder.units = {};
                chargeback.stakeholderMap[stakeholder.id] = stakeholder;

                return stakeholder;
            },
            indexPromise = $http.get('/chargeback/index').then(function(response) {

                var data = response.data.data;
                chargeback.stakeholderMap = {};
                chargeback.stakeholders = data.stakeholders.map(initStakeholder);

                var providers = {};


                chargeback.unitMap = data.accounts.reduce(function(result, account) {

                    account.getKey = function() {

                        return account.id;
                    };
                    var spId = account.service_provider_id;
                    providers[spId] = providers[spId] || {
                        id       : spId,
                        name     : account.service_provider_name,
                        isActive : false
                    };
                    result[account.getKey()] = account;
                    return result;
                }, {});

                var providerArray = _.values(providers);

                providerArray.unshift({
                    id       : null,
                    name     : 'All',
                    isActive : true
                });
                chargeback.setFilter(providerArray[0]);

                chargeback.menus = [
                    {
                        name  : 'Service Provider',
                        items : providerArray
                    }
                ];

                data.chargeback.forEach(function(unit) {

                    var acctId = unit.account_id,
                        stakeId = unit.stakeholder_id;

                    if (chargeback.unitMap[acctId]) {
                        chargeback.unitMap[acctId].stakeholder = chargeback.stakeholderMap[stakeId];
                        chargeback.stakeholderMap[stakeId].units[acctId] = chargeback.unitMap[acctId];
                    }
                });

                return response.data.data;
            }),
            chargeback = {
                title             : 'Chargeback Assignment',
                indexPromise      : indexPromise,
                showAssigned      : false,
                setFilter         : function(provider) {
                    chargeback.selectedFilter = provider;
                },
                isFilteredBy      : function(provider) {
                    return chargeback.selectedFilter === provider;
                },
                getData           : function(widget) {

                    return widget.stakeholder;
                },
                assign            : function(unit, stakeholder) {

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
                unassign          : function(unit, stakeholder) {

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

                    chargeback.stakeholders.unshift(initStakeholder(data));

                    $http.post('/chargeback/createStakeholder', {
                        name  : data.name,
                        email : data.email,
                        title : data.title
                    })
                        .then(function(response) {
                            if (!response.data.success) {
                                throw new Error;
                            }
                            data.id = response.data.data.id;
                        })
                        .catch(function(error) {
                            chargeback.stakeholders.shift();
                        })
                }
            };


        return chargeback;
    });

})(window.angular);
