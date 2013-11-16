(function(ng, undefined) {
    'use strict';

    ng.module('app.breakdown').service('breakdown', function($http, Utils, $rootScope, AccountService) {

        var lastItem = {};
        var service = {
                menus          : [],
                update         : function(item) {

                    if (lastItem.type === item.type &&
                        lastItem.id === item.id &&
                        lastItem.sub_id === item.sub_id) {

                        return;
                    }

                    lastItem = item;

                    var params = {};

                    if (item) {
                        params = {
                            // 'provider' or 'type'
                            type    : item.type,
                            id      : item.id || null,
                            sub_id  : item.sub_id || null,
                            widgets : widgets
                        };
                    }

                    $http.post('/breakdown/update', params).then(function(response) {

                        service.title = response.data.title;

                        service.lastTitle = response.data.lastTitle;

                        service.menus = response.data.menu.concat({
                            name : 'Accounts',
                            items : AccountService.all,
                            pageSize : 5
                        });

                        ng.copy(response.data.widgets, service.widgetData);
                    });
                },
                widgetData     : {},
                registerWidget : function(widget) {

                    var guid;
                    //Just in case there's happens to be the same guid produced twice.
                    while (widgets[guid = Utils.guid()]) {
                    }

                    //A unique identifier so each widget knows what data to get.
                    //This also takes care of multiple widgets of the same type
                    //with different parameters.
                    widget.guid = guid;
                    widget.params = widget.params || null;

                    widgets[widget.guid] = {
                        type : widget.type,
                        params : widget.params
                    };
                },
                getData        : function(widget) {

                    return service.widgetData[widget.guid];
                }
            },
            widgets = {};

        var unregister = $rootScope.$watch(function() {
            // Initialize breakdown with provider type.
            service.update({type : 'provider'});
            unregister();
        });

        return service;
    });

})(window.angular);
