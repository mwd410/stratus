'use strict';

module.exports = function(grunt) {

    var viewConfig = grunt.file.readJSON('config/view.json'),
        jsFiles = viewConfig.all.js.slice(),
        cssFiles = viewConfig.all.css.slice();

    for (var i = 0; i < jsFiles.length; ++i) {
        jsFiles[i] = 'web' + jsFiles[i];
    }

    console.log(jsFiles);
    grunt.initConfig({
        pkg : grunt.file.readJSON('package.json'),
        uglify : {
            options : {
                mangle : false,
                banner : '/*! <%= pkg.name %> v<%= pkg.version %> : <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            prodTarget : {
                files : {
                    'web/js/stratus.min.js' : jsFiles
                }
            }
        },
        less : {
            options : {

            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['uglify']);


};