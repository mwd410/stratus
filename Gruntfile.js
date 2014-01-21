'use strict';

module.exports = function( grunt ) {

    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-less' );
    grunt.loadNpmTasks( 'grunt-ngmin' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );

    var viewConfig = grunt.file.readJSON( 'config/view.json' ),
        jsFiles = viewConfig.dev.js.slice();

    for ( var i = 0; i < jsFiles.length; ++i ) {
        jsFiles[i] = 'web' + jsFiles[i];
    }

    grunt.initConfig( {
        pkg : grunt.file.readJSON( 'package.json' ),

        uglify : {
            options    : {
                mangle : false,
                banner : '/*! <%= pkg.name %> v<%= pkg.version %> : <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            prodTarget : {
                files : {
                    'web/js/stratus.min.js' : jsFiles
                }
            }
        },
        less   : {
            options    : {
                yuicompress : true
            },
            prodTarget : {
                files : {
                    "web/css/stratus.min.css" : "web/css/less/app.less"
                }
            }
        },
        delta  : {
            buildJs  : {
                files : jsFiles,
                tasks : [ 'uglify' ]
            },
            buildCss : {
                files : 'web/css/less/**/*.less',
                tasks : [ 'less' ]
            }
        }
    } );

    grunt.renameTask( 'watch', 'delta' );

    grunt.registerTask( 'watch', ['uglify', 'less', 'delta'] );


};
