(function(ng) {
    'use strict';

    ng.module('app.breakdown')
        .service('BreakdownMenuService', function($http) {

            return {
                getAll : function(type) {

                    return $http.get('/breakdown/menu', {type : type});
                }
            };
        });

})(window.angular);