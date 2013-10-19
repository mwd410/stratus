(function(ng) {
    'use strict';

    ng.module('app.breakdown').service('breakdown', function($http, Utils) {

        var service = {
                menus  : [
                    {
                        name  : 'Breakdown By',
                        items : [
                            {
                                name : 'Service Provider',
                                type : 'provider'
                            },
                            {
                                name : 'Service Type',
                                type : 'type'
                            }
                        ]
                    }
                ],
                lastParams : null,
                update : function(item) {

                    var params = {
                        // so we can get it later without a closure
                        name   : item.name,
                        // 'provider' or 'type'
                        type   : item.type,
                        id     : item.id || null,
                        sub_id : item.sub_id || null
                    };

                    this.lastParams = params;

                    $http.get('/breakdown/update', {params : params})
                        .then(success);
                }
            },
            success = function(response) {

                updateMenu(response);
            },
            updateMenu = function(response) {

                service.menus = response.data.menu;
            };

        return service;
    });

})(window.angular);