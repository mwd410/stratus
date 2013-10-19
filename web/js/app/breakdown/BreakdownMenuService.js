(function(ng) {
    'use strict';

    ng.module('app.breakdown')
        .service('BreakdownMenuService', function($http, Utils) {

            return {
                getAll : function(type) {

                    return $http.get('/breakdown/menu', {
                        params : {type : type}
                    }).then(function(response) {

                            var data = response.data,
                                result = [],
                                items = {},
                                subItems = {};

                            Utils.each(data, function(datum) {

                                var item;

                                if (!items.hasOwnProperty(datum.id)) {

                                    item = {
                                        id       : datum.id,
                                        name     : datum.name,
                                        type     : type,
                                        subItems : []
                                    };

                                    items[datum.id] = item;
                                    subItems[datum.id] = item.subItems;

                                    result.push(item);
                                }

                                subItems[datum.id].push({
                                    id     : datum.id,
                                    sub_id : datum.sub_id,
                                    name   : datum.sub_name,
                                    type   : type
                                });
                            });

                            return result;
                        });
                }
            };
        });

})(window.angular);