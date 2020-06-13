module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        watch: {
            css: {
                files: ['public/scss/**/*.scss'],
                tasks: ['sass'],
                // tasks: ['sass', 'cssmin'],
                options: {
                    spawn: false,
                },
            }
        },
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'public/css',
                    src: ['*.css', '!*.min.css'],
                    dest: 'public/css',
                    ext: '.min.css'
                }]
            }
        },
        uglify: {
            plugins: {
                files: {
                    'public/js/plugins.min.js': [
                        'node_modules/jquery/dist/jquery.min.js',
                        'node_modules/popper.js/dist/umd/popper.min.js',
                        'node_modules/bootstrap/dist/js/bootstrap.min.js',
                        'node_modules/bs-custom-file-input/dist/bs-custom-file-input.min.js',
                        'node_modules/noty/lib/noty.min.js',
                        'vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js',
                    ]
                }
            },
            app: {
                files: {
                    'public/js/app.min.js':
                        ['public/js/app.js']
                },
                compress: true
            },
        },
        sass: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'public/scss/',
                    src: ['**/*.scss'],
                    dest: 'public/css/',
                    ext: '.css'
                }]
            }
        },
        copy: {
            main: {
                files: [
                    {
                        expand: true,
                        cwd: 'node_modules/@fortawesome/fontawesome-free/webfonts/',
                        src: ['**'],
                        dest: 'public/fonts/fontawesome',
                        filter: 'isFile'
                    }
                ],
            },
        },
    })
    ;

    // Load the plugin
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks("grunt-contrib-sass");
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Default task(s).
    grunt.registerTask('default', 'compile');

    grunt.registerTask('compile', ['sass', 'cssmin', 'uglify', 'copy']);
};
