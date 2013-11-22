(function(ng) {
    'use strict';

    ng.module('app.utils').service('Utils', [
        function() {

            var Utils = {
                    apply       : function(obj, vals) {
                        for (var key in vals) {
                            obj[key] = vals[key];
                        }
                        return obj;
                    },
                    applyIf     : function(obj, vals) {
                        for (var key in vals) {
                            if (!(key in obj)) {
                                obj[key] = vals[key];
                            }
                        }
                    },
                    isArray     : ('isArray' in Array) ?
                        Array.isArray :
                        function(value) {
                            return Object.prototype.toString.call(value) === '[object Array]';
                        },
                    each        : function(array, fn, scope) {

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
                    eachKey     : function(object, fn, scope) {

                        for (var key in object) {
                            fn.call(scope || object[key], object[key], key);
                        }
                    },
                    pluck       : function(array, property) {

                        var plucked = [];
                        Utils.each(array, function(item) {

                            plucked.push(item[property]);
                        });
                        return plucked;
                    },
                    guid        : function(object) {

                        //found at http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
                        var guid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                            return v.toString(16);
                        });

                        object && (object.guid = guid);

                        return guid;
                    },
                    getLessVars : function(property) {

                        if (lessCache[property]) {
                            return lessCache[property];
                        }

                        var values = {},
                            foreach = Array.prototype.forEach,
                            ruleId = '#lessVars';

                        foreach.call(document.styleSheets, function(sheet, i) {

                            foreach.call(sheet.cssRules, function(rule, i) {

                                var ruleText = rule.cssText;

                                if (ruleText.slice(0, ruleId.length) === ruleId) {

                                    var ruleKey = ruleText.match(/\.(\w+)/),
                                        regExp = new RegExp(property + '\\s*:\\s*(rgba?\\(\\d+(?:,\\s*\\d+){2}(?:,\\s*\\d*\\.?\\d+?)?\\)|[\\w#]+|\\d+(?:px|em))'),
                                        ruleValue = ruleText.match(regExp);

                                    if (ruleKey && ruleValue) {
                                        values[ruleKey[1]] = ruleValue[1];
                                    }
                                }
                            });
                        });

                        lessCache[property] = values;
                        return values;
                    },
                    mapArrays : function(array, prop1, p_) {

                        var properties = Array.prototype.slice.call(arguments, 1),
                            result = {};

                        array.forEach(function(item) {

                            var key = [];
                            properties.forEach(function(prop) {

                                key.push(item[prop]);
                            });

                            if (!result[key.join('-')]) {
                                result[key.join('-')] = [];
                            }

                            result[key.join('-')].push(item);
                        });

                        return result;
                    },
                    mapObjects : function(array, prop1, p_) {

                        var props = Array.prototype.slice.call(arguments, 1),
                            result = {};

                        array.forEach(function(item) {

                            var key = [];
                            props.forEach(function(prop) {

                                key.push(item[prop]);
                            });

                            result[key.join('-')] = item;
                        });
                    }
                },
                lessCache = {};

            return Utils;
        }
    ]);

})(window.angular);
