'use strict';
var app = angular.module('App', [
    'ngAnimate'
]);

app.controller('MenuController', function($scope) {

    var path = window.location.pathname,
        pathMenus = {
            '/'                   : [
                {
                    url  : '/',
                    name : 'Login'
                }
            ],
            '/accounts|/analysis' : [
                {
                    url  : '/accounts',
                    name : 'Accounts'
                },
                {
                    url  : '/analysis',
                    name : 'Analysis'
                }
            ]
        };

    $scope.currentPath = path;
    $scope.menuOptions = [];

    for (var key in pathMenus) {
        var paths = key.split('|');
        if (paths.indexOf(path) != -1) {
            $scope.menuOptions = pathMenus[key];
        }
    }

});

app.filter('obstructed', function() {
    return function(input, size) {
        if (typeof size === 'undefined') {
            size = 4;
        }
        return input.slice(0, size) + '************' + input.slice(-size);
    };
});

String.prototype.repeat = function(length) {
    return new Array(length + 1).join(this);
};

//less.watch();
var Utils = {
    apply : function(obj, vals) {
        for (var key in vals) {
            obj[key] = vals[key];
        }
        return obj;
    },
    applyIf : function(obj, vals) {
        for (var key in vals) {
            if (!(key in obj)) {
                obj[key] = vals[key];
            }
        }
    },
    isArray : ('isArray' in Array) ?
        Array.isArray :
        function(value) {
            return Object.prototype.toString.call(value) === '[object Array]';
        },
    each : function(array, fn, scope) {

        if (Utils.isArray(array)) {

            for (var i = 0, len = array.length; i < len; ++i) {
                if (false === fn.call(scope || array[i], array[i], i, len)) {
                    return i;
                }
            }
        } else {
            fn.call(scope || array, array, 0, 1);
        }
        return true;
    },
    eachKey : function(object, fn, scope) {

        for (var key in object) {
            fn.call(scope || object[key], object[key], key);
        }
    }
};