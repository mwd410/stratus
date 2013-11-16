(function(ng, undefined) {
    'use strict';
    
    ng.module('app').filter('page', function() {
    
        return function(input, page) {

            page = parseInt(page);
            return input.slice(page);
        };
    });
    
})(window.angular);
