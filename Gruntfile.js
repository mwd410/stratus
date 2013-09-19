'use strict';

module.exports = function(grunt) {

    var viewConfig = grunt.file.readJSON('config/view.json'),
        jsFiles = viewConfig.dev.js.slice();

    for (var i = 0; i < jsFiles.length; ++i) {
        jsFiles[i] = 'web' + jsFiles[i];
    }

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
                yuicompress : true
            },
            prodTarget : {
                files : {
                    "web/css/stratus.min.css" : "web/css/less/app.less"
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grung-ngmin');

    grunt.registerTask('default', ['uglify', 'less']);


};