module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    copy: {
      main: {
        files: [
          {src: 'src/shared/mi_request.php', dest: 'src/du/engine/classes/moneyinst/mi_request.php'},
          {src: 'src/shared/mi_request.php', dest: 'src/dw/engine/classes/moneyinst/mi_request.php'},
          {src: 'src/shared/mi_request.php', dest: 'src/j/moneyinst/mi_request.php'},
          {src: 'src/shared/mi_request.php', dest: 'src/j25/moneyinst/mi_request.php'},
          {src: 'src/shared/mi_request.php', dest: 'src/w/moneyinst/mi_request.php'},
          {src: 'src/shared/miobfs.js', dest: 'src/dw/engine/classes/moneyinst/miobfs.js'},
          {src: 'src/shared/miobfs.js', dest: 'src/du/engine/classes/moneyinst/miobfs.js'},
          {cwd: 'src/images/', src: '**', dest: 'files/images/', expand: true},
        ]
      },
      main1: {
        src: 'src/uc/ucoz.js',
        dest: 'files/ucoz.js',
        options: {
          process: function (content, srcpath) {
            content = '<script type="text/javascript">' + content + '</script>';
            return content;
          },
        },
      },
      main2: {
        src: 'src/shared/miobfs.js',
        dest: 'src/j/moneyinst/mi-clear.js',
        options: {
          process: function (content, srcpath) {
            content = content.replace(/\/engine\/classes\/moneyinst\/mi_request.php/g,"/plugins/system/moneyinst/mi_request.php");
            return content;
          },
        },
      },
      main3: {
        src: 'src/shared/miobfs.js',
        dest: 'src/j25/moneyinst/mi-clear.js',
        options: {
          process: function (content, srcpath) {
            content = content.replace(/\/engine\/classes\/moneyinst\/mi_request.php/g,"/plugins/system/moneyinst/mi_request.php");
            return content;
          },
        },
      },
      main4: {
        src: 'src/shared/miobfs.js',
        dest: 'src/w/moneyinst/mi-clear.js',
        options: {
          process: function (content, srcpath) {
            content = content.replace(/\/engine\/classes\/moneyinst\/mi_request.php/g,"/wp-content/plugins/moneyinst/mi_request.php");
            return content;
          },
        },
      },
      main5: {
        src: 'src/index.html',
        dest: 'files/js_code.html',
        options: {
          process: function (content, srcpath) {
            content = content.replace(/<!doctype html><html lang="ru">/g,'{% extends "ui/auth.html" %}');
            content = content.replace(/<head><meta charset="UTF-8"><title>FAQ<\/title><\/head>/g,'{% load static %}');
            content = content.replace(/<body>/g,'{% block content %}');
            content = content.replace(/<\/body><\/html>/g,'{% endblock %}');
            content = content.replace(/images\//g,"{% static 'ui/images/");
            content = content.replace(/\.png/g,".png' %}");
            content = content.replace(/files\//g,"/scripts/");
            return content;
          },
        },
      },
    },
    uglify: {
      my_target1: {files: {'src/du/engine/classes/moneyinst/miobfs.js': ['src/du/engine/classes/moneyinst/miobfs.js']}},
      my_target2: {files: {'src/dw/engine/classes/moneyinst/miobfs.js': ['src/dw/engine/classes/moneyinst/miobfs.js']}},
      my_target3: {files: {'src/uc/ucoz.js': ['src/uc/ucoz-clear.js']}},
      my_target4: {files: {'src/un/moneyinst.js': ['src/un/moneyinst-clear.js']}},
      my_target5: {files: {'src/j/moneyinst/mi-clear.js': ['src/j/moneyinst/mi-clear.js']}},
      my_target6: {files: {'src/j25/moneyinst/mi-clear.js': ['src/j25/moneyinst/mi-clear.js']}},
      my_target7: {files: {'src/w/moneyinst/mi-clear.js': ['src/w/moneyinst/mi-clear.js']}},
    },
    jsObfuscate: {
      options: {
        concurrency: 2,
        keepLinefeeds: false,
        keepIndentations: false,
        encodeStrings: true,
        encodeNumbers: true,
        moveStrings: true,
        replaceNames: true,
        variableExclusions: [ '^_get_', '^_set_', '^_mtd_' ]
      },
      test1: {files: {'src/du/engine/classes/moneyinst/miobfs.js': ['src/du/engine/classes/moneyinst/miobfs.js']}},
      test2: {files: {'src/dw/engine/classes/moneyinst/miobfs.js': ['src/dw/engine/classes/moneyinst/miobfs.js']}},
      test3: {files: {'src/uc/ucoz.js': ['src/uc/ucoz.js']}},
      test4: {files: {'src/un/moneyinst.js': ['src/un/moneyinst.js']}},
      test5: {files: {'src/j/moneyinst/mi-clear.js': ['src/j/moneyinst/mi-clear.js']}},
      test6: {files: {'src/j25/moneyinst/mi-clear.js': ['src/j25/moneyinst/mi-clear.js']}},
      test7: {files: {'src/w/moneyinst/mi-clear.js': ['src/w/moneyinst/mi-clear.js']}},
    },
    compress: {
      main1: {options: {archive: 'files/dle_utf.zip'},files: [{expand: true, cwd: 'src/du/', src: ['**'], dot:true}]},
      main2: {options: {archive: 'files/dle_win1251.zip'},files: [{expand: true, cwd: 'src/dw/', src: ['**'], dot:true}]},
      main3: {options: {archive: 'files/moneyinst_wp.zip'},files: [{expand: true, cwd: 'src/w/', src: ['**']}]},
      main4: {options: {archive: 'files/j/moneyinst.zip'},files: [{expand: true, cwd: 'src/j/', src: ['**']}]},
      main5: {options: {archive: 'files/j25/moneyinst.zip'},files: [{expand: true, cwd: 'src/j25/', src: ['**']}]},
      main6: {options: {archive: 'files/moneyinst.zip'},files: [{expand: true, cwd: 'src/un/', src: ['moneyinst.js']}]},
    },
    remove: {
      main1:{
        options: {
          trace: true
        },
        fileList: ['src/du/engine/classes/moneyinst/mi_request.php', 'src/dw/engine/classes/moneyinst/mi_request.php',
        'src/j/moneyinst/mi_request.php', 'src/j25/moneyinst/mi_request.php', 'src/w/moneyinst/mi_request.php',
        'src/dw/engine/classes/moneyinst/miobfs.js', 'src/du/engine/classes/moneyinst/miobfs.js',
        'src/j/moneyinst/mi-clear.js', 'src/j25/moneyinst/mi-clear.js', 'src/w/moneyinst/mi-clear.js',
        'src/uc/ucoz.js', 'src/un/moneyinst.js'],
      },
      main2:{
        options: {
          trace: true
        },
         fileList: ['src/du/engine/classes/moneyinst/mi_request.php', 'src/dw/engine/classes/moneyinst/mi_request.php',
        'src/j/moneyinst/mi_request.php', 'src/j25/moneyinst/mi_request.php', 'src/w/moneyinst/mi_request.php',
        'src/dw/engine/classes/moneyinst/miobfs.js', 'src/du/engine/classes/moneyinst/miobfs.js',
        'src/j/moneyinst/mi-clear.js', 'src/j25/moneyinst/mi-clear.js', 'src/w/moneyinst/mi-clear.js',
        'src/uc/ucoz.js', 'src/un/moneyinst.js'],
        dirList: ['files/'],
      },
    },
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('js-obfuscator');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-remove');

  // Default task(s).
  grunt.registerTask('default', ['copy:main', 'copy:main2', 'copy:main3', 'copy:main4', 'copy:main5', 'uglify', 'jsObfuscate', 'compress', 'copy:main1', 'remove:main1']);
  grunt.registerTask('clear-all', ['remove:main2']);

};
