(function(ng) {
    'use strict';

    ng.module('app.nav').service('NavService', function($state) {

        var menus = {
            app    : [
                {
                    state : 'app.overview',
                    name  : 'Overview'
                },
                {
                    state : 'app.breakdown',
                    name  : 'Breakdown'
                },
                {
                    state : 'app.chargeback',
                    name  : 'Chargeback'
                },
                {
                    state : 'app.alerts',
                    name  : 'Alerts'
                },
                {
                    state : 'app.reports',
                    name  : 'Reports'
                },
                {
                    state : 'app.savings',
                    name  : 'Savings'
                }
            ],
            config : [
                {
                    name    : 'Profile',
                    iconCls : 'icon-cog',
                    state   : 'app.profile'
                },
                {
                    name    : 'Providers',
                    state   : 'app.providers',
                    iconCls : 'icon-list-ul'
                },
                {
                    name    : 'Logout',
                    iconCls : 'icon-signout',
                    href    : '/logout'
                }
            ]
        };

        var expandedMenu = null,
            availableMenus = {

            };

        return {
            registerMenu   : function(menu) {

                availableMenus[menu] = true;
            },
            isAvailable    : function(menu) {

                return !!availableMenus[menu];
            },
            isExpanded     : function(menu) {

                return expandedMenu === menu;
            },
            expandMenu     : function(menu) {

                if (expandedMenu === menu) {
                    expandedMenu = null;
                } else {
                    expandedMenu = menu;
                }
            },
            getMenuItems   : function() {

                var items;

                if ($state.current.name.slice(0, 3) === 'app') {
                    items = menus.app;
                }

                availableMenus.main = items && items.length > 0;

                return items;
            },
            getConfigItems : function($scope) {

                var items;

                if ($state.current.name.slice(0, 3) === 'app') {
                    items = menus.config;
                }

                availableMenus.config = items && items.length > 0;

                return items;
            },
            isActive       : function(item) {

                return $state.current.name === item.state;
            }
        };
    });

})(window.angular);