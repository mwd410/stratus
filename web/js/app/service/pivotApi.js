(function(ng, undefined) {
    'use strict';

    ng.module('app.service').factory('pivotApi', function($http, _) {

        var pivots = {
                provider : 'product',
                type     : 'category'
            },
            api = {
                data             : {},
                promise          : $http.get('/pivot').then(function(response) {

                    var data = response.data.data;

                    _.each(pivots, function(minor, major) {

                        var minorItems = _.chain(data[major])
                            .map(function(item) {

                                return {
                                    id      : item.type_id,
                                    name    : item.type_name,
                                    subId   : item.sub_type_id,
                                    subName : item.sub_type_name
                                };
                            });

                        api.data[major] = minorItems
                            .uniq('id')
                            .map(function(item) {

                                return {
                                    id   : item.id,
                                    name : item.name
                                };
                            }).value();

                        api.data[minor] = minorItems.reduce(function(result, item) {

                            result[item.id] = result[item.id] || [];
                            result[item.id].push(item);

                            return result;
                        }, {}).value();
                    });

                    api.data.pivotTypes = data.pivotTypes;

                    return data;
                }),
                getPivotTypes    : function() {

                    return api.data.pivotTypes;
                },
                getPivotTypeName : function(type) {

                    if (type == 'provider'
                        || type == 1) {

                        return 'provider';
                    } else if (type == 'type'
                        || type == 2) {

                        return 'type';
                    } else {
                        throw new Error('Invalid pivot type ' + type);
                    }
                },
                getMajor         : function(pivotType) {

                    var typeName = this.getPivotTypeName(pivotType);

                    return api.data[typeName];
                },
                getMinor         : function(pivotType, id) {

                    var typeName = this.getPivotTypeName(pivotType),
                        minorName = pivots[typeName];

                    return api.data[minorName][id];
                }
            };

        return api;
    });

})(window.angular);
