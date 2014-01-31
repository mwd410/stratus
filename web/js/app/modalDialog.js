(function(ng) {
    'use strict';

    ng.module('app').factory('modalDialog', function($q, $rootScope) {

        var queue = [];

        return function(config) {

            config.title = config.title || 'Alert';
            config.buttons = config.buttons || [
                {
                    text : 'Ok',
                    iconCls : 'icon-ok'
                }
            ];
            config.callback = config.callback || function() {};

            var selectedOption = $q.defer();
            config.onClick = function(buttonText) {

                selectedOption.resolve(buttonText);
                queue.shift();
                $rootScope.modalDialog = queue[0];
            };

            if (!$rootScope.modalDialog) {
                $rootScope.modalDialog = config;
            } else {
                queue.push(config);
            }

            return selectedOption.promise;
        };
    });

})(window.angular);
