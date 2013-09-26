'use strict';
var app = angular.module('App', [
    'ngAnimate',
    'ui.router'
])
    .config(function($stateProvider, $urlRouterProvider) {

        $urlRouterProvider.otherwise('/main/overview');

        $stateProvider
            .state('app', {
                url : '/main',
                templateUrl : 'partials/app.html'
            })
            .state('app.overview', {
                url : '/overview',
                templateUrl : 'partials/overview.html'
            })
            .state('app.breakdown', {
                url : '/breakdown',
                templateUrl : 'partials/breakdown.html'
            })
            .state('app.chargeback', {
                url         : '/chargeback',
                templateUrl : 'partials/chargeback.html'
            })
            .state('app.alerts', {
                url         : '/alerts',
                templateUrl : 'partials/alerts.html'
            })
            .state('app.reports', {
                url         : '/reports',
                templateUrl : 'partials/reports.html'
            })
            .state('app.savings', {
                url         : '/savings',
                templateUrl : 'partials/savings.html'
            })
            .state('app.profile', {
                url : '/profile',
                templateUrl: 'partials/profile.html'
            })
            .state('app.providers', {
                url : '/providers',
                templateUrl: 'partials/providers.html'
            })
        ;
    });


app.controller('LeftNavController', function($scope, NavService) {

    $scope.menu = NavService;
});

app.controller('MenuController', function($scope, NavService) {

    var path = window.location.pathname,
        pathMenus = {
            '/'                   : [],
            '/accounts|/dashboard' : [
                {
                    url  : '/accounts',
                    name : 'Overview'
                },
                {
                    url  : '/dashboard',
                    name : 'Breakdown'
                },
                {
                    url : '/chargeback',
                    name : 'Chargeback'
                },
                {
                    url : '/alerts',
                    name : 'Alerts'
                },
                {
                    url : '/reports',
                    name : 'Reports'
                },
                {
                    url : '/savings',
                    name : 'Savings'
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

    $scope.menu = NavService;
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
    },
    pluck : function(array, property) {

        var plucked = [];
        Utils.each(array, function(item) {

            plucked.push(item[property]);
        });
        return plucked;
    }
};