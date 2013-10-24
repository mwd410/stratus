(function(ng, undefined) {
    'use strict';

    ng.module('app.breakdown').service('breakdown', function($http, Utils) {

        var service = {
                menus          : [],
                update         : function(item) {

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

                        service.menus = response.data.menu;

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

        service.update();

        return service;
    });

})(window.angular);