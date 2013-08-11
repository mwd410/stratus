'use strict';

var Record = function(datas, id) {

    this.data = Utils.apply({}, data);

    if (typeof id === 'undefined') {
        this.id = data.id;
    } else {
        this.id = id;
    }

    this.modified = {};
};

Record.prototype = {
    get : function(name) {
        return this.data[name];
    },
    set : function(name, value) {

        if (this.data[name] != value &&
            !(name in this.modified)) {

            this.modified[name] = this.data[name];
        }

        this.data[name] = value;
    },
    revert : function() {

        Utils.apply(this.data, this.modified);
    },
    isModified : function() {

        return Object.keys(this.modified).length > 0;
    }
};