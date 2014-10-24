module.exports = function(grunt) {

	// show elapsed time at the end
  require('time-grunt')(grunt);
  // load all grunt tasks
  require('load-grunt-tasks')(grunt);

	grunt.initConfig({

		uglify: {
			lib: {
				files: {
					'assets/js/lib.js': [
						'assets/js/lib/underscore/underscore.js'
					]
				}
			}
		},

		sass: {
	    main: {
	      options: { style: 'expanded' },
	      files: { 'style.css':'assets/scss/style.scss' }
	    }
	  },

	  autoprefixer: {
	    main: {
	      options: {
	        browsers: ['> 1%', 'last 2 versions', 'ie >= 9']
	      },
	      src: 'style.css',
	      dest: 'style.css'
	    }
	  },

	  mincss: {
	    main: {
	      files: {
	        'style.css':'style.css'
	      }
	    }
	  },

	  watch: {
	    styles: {
	      files: ['assets/scss/**/*.*'],
	      tasks: ['sass', 'autoprefixer']
	    }
	  },

	  modernizr: {

			dist: {
				// [REQUIRED] Path to the build you're using for development.
				'devFile' : 'assets/js/lib/modernizr-dev.js',

				// [REQUIRED] Path to save out the built file.
				'outputFile' : 'assets/js/modernizr.js',

				// Based on default settings on http://modernizr.com/download/
				'extra' : {
					'shiv' : true,
					'printshiv' : false,
					'load' : false,
					'mq' : false,
					'cssclasses' : true
				},

				// Based on default settings on http://modernizr.com/download/
				'extensibility' : {
					'addtest' : false,
					'prefixed' : true,
					'teststyles' : false,
					'testprops' : true,
					'testallprops' : true,
					'hasevents' : true,
					'prefixes' : true,
					'domprefixes' : true
				},

				// By default, source is uglified before saving
				'uglify' : true,

				// Define any tests you want to implicitly include.
				'tests' : [
					'csstransforms',
					'touch'
				],

				// By default, this task will crawl your project for references to Modernizr tests.
				// Set to false to disable.
				'parseFiles' : false,

				// When parseFiles = true, this task will crawl all *.js, *.css, *.scss files, except files that are in node_modules/.
				// You can override this by defining a 'files' array below.
				// 'files' : ['assets/css/screen.css'],

				// When parseFiles = true, matchCommunityTests = true will attempt to
				// match user-contributed tests.
				'matchCommunityTests' : true,

				// Have custom Modernizr tests? Add paths to their location here.
				'customTests' : []
			}

	  }

	});

	grunt.registerTask('dev', [ 'sass', 'autoprefixer' ]);
	grunt.registerTask('setup', [ 'uglify', 'modernizr', 'dev' ]);
	grunt.registerTask('prod', [ 'setup', 'mincss' ]);
	grunt.registerTask('default', [ 'dev', 'watch' ]);

};
