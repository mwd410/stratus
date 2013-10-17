(function(ng) {
    'use strict';

    ng.module('app', [
            'ui.router',
            'app.account',
            'app.breakdown',
            'app.dashboard',
            'app.nav',
            'app.utils'
        ])
        .config(function($stateProvider, $urlRouterProvider) {

            $urlRouterProvider.otherwise('/main/overview');

            $stateProvider
                .state('app', {
                    url         : '/main',
                    templateUrl : 'partials/app.html'
                })
                .state('app.overview', {
                    url         : '/overview',
                    templateUrl : 'partials/overview.html'
                })
                .state('app.breakdown', {
                    url         : '/breakdown',
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
                    url         : '/profile',
                    templateUrl : 'partials/profile.html'
                })
                .state('app.providers', {
                    url         : '/providers',
                    templateUrl : 'partials/providers.html'
                })
            ;
        });

    ng.module('app.account', [
        'ngAnimate'
    ]);

    ng.module('app.breakdown', []);

    ng.module('app.dashboard', []);

    ng.module('app.nav', []);

    ng.module('app.utils', []);

})(window.angular);


angular.module('app').controller('LeftNavController', function($scope, NavService, BreakdownMenuService) {

    $scope.menu = NavService;
    $scope.menus = [
        {
            title : 'Breakdown By',
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
    ];

    function setType(type, title) {

        BreakdownMenuService.getAll(type).then(function(result) {

            $scope.menus[1] = {
                title : title,
                items : result
            };
        });
    }

    $scope.itemClick = function(item) {

        if (item.type) {
            setType(item.type, item.name);
        }
    };
});

angular.module('app').controller('MenuController', function($scope, NavService) {

    $scope.menu = NavService;
});

$.fn.padding = function() {

    var directions = [
            'top',
            'right',
            'bottom',
            'left'
        ],
        i,
        direction,
        dirPadding,
        regex = /(\d+)(em)?/,
        match,
        unit,
        value,
        result = {};

    for (i = 0; i < directions.length; ++i) {
        direction = directions[i];

        dirPadding = this.css('padding-' + direction);
        match = dirPadding.match(regex);

        value = match[1];
        unit = match[2];

        if (unit === 'em') {
            value = ConvertEmToPx(value);
        }

        result[direction] = value;
    }

    return result;
};