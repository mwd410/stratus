(function(ng) {
    'use strict';

    ng.module('app.breakdown').service('breakdown', function($http) {

        var service = {
                menus  : [],
                update : function(item) {

                    var params = {};

                    if (item) {
                        params = {
                            // 'provider' or 'type'
                            type   : item.type,
                            id     : item.id || null,
                            sub_id : item.sub_id || null
                        };
                    }

                    $http.get('/breakdown/update', {params : params})
                        .then(function(response) {

                            if (item) {
                                service.title = item.title;
                            }

                            service.menus = response.data.menu;

                            service.projection = response.data.projection;
                        });
                }
            };

        service.update();

        return service;
    });

})(window.angular);