module.exports = function(grunt) {

	grunt.initConfig({
		sass: {
			options: {
				sourceMap: false
			},
			dist: {
				files: {
					'./inc/assets/admin/css/eer-admin-settings.css': './assets/admin/scss/eer-admin-settings.scss',
					'./inc/assets/web/css/eer-web.css': './assets/web/scss/eer-web.scss',
				}
			}
		},
		cssmin: {
			target: {
				files: [{
					expand: true,
					cwd: './inc/assets/admin/css/',
					src: ['*.css', '!*.min.css'],
					dest: './inc/assets/admin/css/',
					ext: '.min.css'
				}]
			}
		},
		concat: {
			adminJs: {
				src: ['./assets/admin/js/eer-production.js'],
				dest: './inc/assets/admin/js/eer-production.js'
			},
			webJs: {
				src: ['./assets/web/js/eer-web.js'],
				dest: './inc/assets/web/js/eer-web.js'
			}
		}
	});

	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-minified');
	grunt.loadNpmTasks('grunt-contrib-concat');

	grunt.registerTask('default', ['sass', 'cssmin', 'concat']);

};