module.exports = function(grunt) {

    // 1. All configuration goes here
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            basic_and_extras: {
                files: {
                    'js/build/production.js': ['js/fitvids.js', 'js/functions.js'],
                    'js/build/customizer.js': ['js/customizer.js', 'js/multiple-select.js']
                }
            }
        },
        uglify: {
            dist: {
                files: {
                    'js/build/production.min.js' : 'js/build/production.js',
                    'js/build/customizer.min.js' : 'js/build/customizer.js',
                    'js/build/postMessage.min.js' : 'js/postMessage.js',
                    'js/build/html5shiv.min.js' : 'js/html5shiv.js',
                    'js/build/respond.min.js' : 'js/respond.js'
                }
            }
        },
        watch: {
            scripts: {
                files: ['js/*.js'],
                tasks: ['concat', 'uglify'],
                options: {
                    spawn: false
                }
            },
            css: {
                files: ['sass/*.scss'],
                tasks: ['sass', 'cssjanus', 'postcss', 'cssmin'],
                options: {
                    livereload: true,
                    spawn: false
                }
            }
        },
        sass: {
            options: {
                implementation: require('sass'),
                outputStyle: 'expanded',
                sourceMap: true,
                // the partials still use @import and slash division, which Dart Sass 1.x warns about
                silenceDeprecations: ['import', 'slash-div', 'global-builtin']
            },
            dist: {
                files: {
                    'style.css': 'sass/style.scss',
                    'styles/customizer.css': 'sass/customizer.scss',
                    'styles/admin.css': 'sass/admin.scss',
                    'styles/editor-style.css': 'sass/editor-style.scss'
                }
            }
        },
        postcss: {
            options: {
                processors: [
                    require('autoprefixer')({
                        overrideBrowserslist: ['last 1 version', '> 1%', 'ie 8']
                    })
                ]
            },
            dist: {
                // runs after cssjanus so prefixed copies don't separate /* @noflip */ from its declaration
                files: {
                    'style.css': 'style.css',
                    'style-rtl.css': 'style-rtl.css',
                    'styles/customizer.css': 'styles/customizer.css',
                    'styles/admin.css': 'styles/admin.css'
                }
            }
        },
        cssjanus: {
            dev: {
                options: {
                    swapLtrRtlInUrl: false // replace 'ltr' with 'rtl'
                },
                src: ['style.css'],
                dest: 'style-rtl.css'
            }
        },
        cssmin: {
            combine: {
                files: {
                    'style.min.css': ['style.css'],
                    'styles/customizer.min.css': ['styles/customizer.css'],
                    'styles/admin.min.css': ['styles/admin.css'],
                    'style-rtl.min.css': ['style-rtl.css']
                }
            }
        },
        compress: {
            main: {
                options: {
                    archive: '/Users/bensibley/Desktop/author.zip'
                },
                files: [
                    {
                        src: ['**', '!node_modules/**','!sass/**', '!gruntfile.js', '!package.json', '!style-prefixed.css','!/.git/','!/.idea/','!/.sass-cache/','!**.DS_Store'],
                        filter: 'isFile'
                    }
                ]
            }
        },
        makepot: {
            target: {
                options: {
                    domainPath: '/languages',
                    exclude: ['library/.*/.*'],
                    potFilename: 'author.pot',
                    type: 'wp-theme',
                    processPot: function( pot ) {
                        var translation,
                            excluded_meta = [
                                'Theme Name of the plugin/theme',
                                'Theme URI of the plugin/theme',
                                'Author of the plugin/theme',
                                'Author URI of the plugin/theme'
                            ];

                        for ( translation in pot.translations[''] ) {
                            if ( 'undefined' !== typeof pot.translations[''][ translation ].comments.extracted ) {
                                if ( excluded_meta.indexOf( pot.translations[''][ translation ].comments.extracted ) >= 0 ) {
                                    console.log( 'Excluded meta: ' + pot.translations[''][ translation ].comments.extracted );
                                    delete pot.translations[''][ translation ];
                                }
                            }
                        }

                        return pot;
                    }
                }
            }
        },
        excludeFiles: '--exclude "*.gitignore" --exclude ".sass-cache/" --exclude "*.DS_Store" --exclude ".git/" --exclude ".idea/" --exclude "gruntfile.js" --exclude "node_modules/" --exclude "package.json" --exclude "sass/" --exclude "styles/admin.css.map" --exclude "styles/customizer.css.map" --exclude "styles/editor-style.css.map" --exclude "package-lock.json" --exclude "style.css.map"',
        shell: {
            zip: {
                command: [
                    // delete existing copies on Desktop (if they exist)
                    'rm -R "/Users/bensibley/Dropbox/Compete Themes/Distribution/author" || true',
                    'rm -R "/Users/bensibley/Dropbox/Compete Themes/Distribution/author.zip" || true',
                    // copy plugin folder to desktop without any project/meta files
                    'rsync -r "/Users/bensibley/Dropbox/Compete Themes/Development/author" "/Users/bensibley/Dropbox/Compete Themes/Distribution/" <%= excludeFiles %>',
                    // open desktop
                    'cd "/Users/bensibley/Dropbox/Compete Themes/Distribution/"',
                    // zip the author folder on desktop
                    'zip -r author.zip author'
                ].join('&&')
            }
        }
    });

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('@lodder/grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-cssjanus');
    grunt.loadNpmTasks('grunt-shell');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat', 'uglify', 'watch', 'sass', 'cssjanus', 'postcss', 'cssmin', 'compress', 'makepot', 'shell']);

};