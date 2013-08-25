'use strict';

module.exports = function(grunt) {

    var viewConfig = grunt.file.readJSON('config/view.json'),
        jsFiles = viewConfig.all.js.slice();

    grunt.initConfig({
        pkg : grunt.file.readJSON('package.json'),
        uglify : {
            banner : '/*! <%= pkg.name %> v<%= pkg.version %> : <%= grunt.template.today("yyyy-mm-dd") %> */\n',
            files : {
                'web/js/stratus.min.js' : jsFiles
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['uglify']);

    
};