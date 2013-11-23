(function(ng, undefined) {
    'use strict';

    ng.module('app.breakdown').factory('breakdown', function($http, Utils, $rootScope, AccountService, $q) {

        var service = {
                menus          : [],
                update         : function(item) {

                    if (item.isActive) {
                        return;
                    }

                    $http.post('/breakdown/update', {
                        // 'provider' or 'type'
                        type    : item.type,
                        id      : item.id || null,
                        sub_id  : item.sub_id || null,
                        widgets : widgets
                    }).then(function(response) {

                        service.title = response.data.title;

                        service.lastTitle = response.data.lastTitle;

                        AccountService.accounts.then(function(accounts) {

                            service.menus = response.data.menu.concat({
                                name     : 'Accounts',
                                items    : accounts,
                                pageSize : 5
                            });
                        });

                        ng.copy(response.data.widgets, service.widgetData);
                    });
                },
                widgetData     : {},
                registerWidget : function(widget) {

                    // TODO
                    // make this work by having widgets
                    // come from a (this?) service rather than the controller.
                    /*if (widget.guid) {
                        return;
                    }*/

                    var guid;
                    //Just in case there's happens to be the same guid produced twice.
                    while (widgets[guid = Utils.guid()]) {}

                    //A unique identifier so each widget knows what data to get.
                    //This also takes care of multiple widgets of the same type
                    //with different parameters.
                    widget.guid = guid;
                    widget.params = widget.params || null;

                    widgets[widget.guid] = {
                        type   : widget.type,
                        params : widget.params
                    };
                },
                getData        : function(widget) {

                    return service.widgetData[widget.guid];
                },
                // TODO
                // once registerWidget is fixed, remove this.
                clean : function() {
                    widgets = {};
                },
                init : function() {
                    // Initialize breakdown with provider type.
                    service.update({type : 'provider'});
                }
            },
            widgets = {};

        return service;
    });

})(window.angular);
