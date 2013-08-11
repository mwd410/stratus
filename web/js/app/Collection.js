'use strict';
var Collection;

(function() {

    var genKey = 0;

    Collection = function(getKeyFn) {

        this.items = [];
        this.map = {};
        this.keys = [];
        this.length = 0;

        this.adds = {};
        this.edits = {};
        this.deletes = {};

        if (getKeyFn) {
            this.getKeyFn = getKeyFn.bind(this);
        }
    };

    Collection.prototype = {
        getKeyFn    : function(o) {

            return o.id;
        },
        add         : function(object, key) {

            if (typeof key === 'undefined') {
                key = this.getKeyFn(object);
            }

            if (typeof this.map[key] !== 'undefined') {
                return this.replace(object, key);
            }

            this.map[key] = object;
            this.keys.push(key);
            this.items.push(object);
            this.length++;
        },
        remove      : function(object) {

            return this.removeAt(this.indexOf(object));
        },
        removeAt    : function(index) {

            if (!this.items[index]) {
                return false;
            }

            var key = this.keys[index];
            this.keys.splice(index, 1);
            this.items.splice(index, 1);
            delete this.map[key];
            this.length--;

            return true;
        },
        removeKey   : function(key) {

            return this.removeAt(this.keys.indexOf(key));
        },
        replace     : function(object, key) {

            if (typeof key === 'undefined') {
                key = object.id;
            }

            var old = this.map[key],
                index = this.keys;

            this.items[index] = object;
            this.map[key] = object;

            return old;
        },
        indexOf     : function(object) {

            return this.items.indexOf(object);
        },
        get         : function(key) {
            return this.map[key];
        },
        getAt       : function(index) {

            return this.items[index];
        },
        getKey      : function(object) {

            return this.keys[this.indexOf(object)];
        },
        each        : function(fn, scope) {

            var items = this.items.slice(0),
                keys = this.keys.slice(0),
                len = items.length;

            for (var i = 0; i < this.items.length; ++i) {

                var cont = fn.call(scope || items[i], items[i], i, keys[i],
                    len);
                if (cont === false) {
                    return i;
                }
            }

            return true;
        },
        addAll      : function(objects) {

            if (Utils.isArray(objects)) {
                Utils.each(objects, function(o) {
                    this.add(o);
                }, this);
            } else {
                Utils.eachKey(objects, function(o, key) {
                    this.add(o, key);
                }, this);
            }
        },
        clear       : function() {

            this.items = [];
            this.keys = [];
            this.map = {};
            this.length = 0;
        },
        clone       : function() {

            var clone = new Collection(this.getKeyFn);
        },
        contains    : function(object) {
            return this.indexOf(object) !== -1;
        },
        containsKey : function(key) {
            return this.keys.indexOf(key) !== -1;
        },
        newKey      : function() {

        },
        beginAdd    : function(object) {

            object.id = ['new', ++genKey].join('_');
            this.adds[object.id] = object;

            this.add(object);
        },
        commit      : function(object) {


        }
    };
})();

