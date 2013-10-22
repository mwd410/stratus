(function(ng) {
    'use strict';

    ng.module('app.utils').service('Utils', [
        function() {

            var Utils = {
                apply   : function(obj, vals) {
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
                each    : function(array, fn, scope) {

                    if (Utils.isArray(array)) {

                        for (var i = 0, len = array.length; i < len; ++i) {
                            if (false === fn.call(scope || array[i], array[i], i, len)) {
                                return i;
                            }
                        }
                    } else if (array) {
                        fn.call(scope || array, array, 0, 1);
                    }
                    return true;
                },
                eachKey : function(object, fn, scope) {

                    for (var key in object) {
                        fn.call(scope || object[key], object[key], key);
                    }
                },
                pluck   : function(array, property) {

                    var plucked = [];
                    Utils.each(array, function(item) {

                        plucked.push(item[property]);
                    });
                    return plucked;
                }
            };

            return Utils;
        }
    ]);

})(window.angular);