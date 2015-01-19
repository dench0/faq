module.exports = {
  main: {
    files: [{
      src: 'src/shared/mi_request.php',
      dest: 'src/du/engine/classes/moneyinst/mi_request.php'
    }, {
      src: 'src/shared/mi_request.php',
      dest: 'src/dw/engine/classes/moneyinst/mi_request.php'
    }, {
      src: 'src/shared/mi_request.php',
      dest: 'src/j/moneyinst/mi_request.php'
    }, {
      src: 'src/shared/mi_request.php',
      dest: 'src/j25/moneyinst/mi_request.php'
    }, {
      src: 'src/shared/mi_request.php',
      dest: 'src/w/moneyinst/mi_request.php'
    }, {
      src: 'src/shared/miobfs.js',
      dest: 'src/dw/engine/classes/moneyinst/miobfs.js'
    }, {
      src: 'src/shared/mi_request.php',
      dest: 'src/php/moneyinst/mi_request.php'
    }, {
      src: 'src/shared/miobfs.js',
      dest: 'src/du/engine/classes/moneyinst/miobfs.js'
    }, {
      cwd: 'src/images/',
      src: '**',
      dest: 'files/images/',
      expand: true
    }, ]
  },
  main1: {
    src: 'src/uc/ucoz.js',
    dest: 'files/ucoz.js',
    options: {
      process: function(content, srcpath) {
        content = '<script type="text/javascript">' + content + '</script>';
        return content;
      },
    },
  },
  main2: {
    src: 'src/shared/miobfs.js',
    dest: 'src/j/moneyinst/mi-clear.js',
    options: {
      process: function(content, srcpath) {
        content = content.replace(/\/engine\/classes\/moneyinst\/mi_request.php/g, "/plugins/system/moneyinst/mi_request.php");
        return content;
      },
    },
  },
  main3: {
    src: 'src/shared/miobfs.js',
    dest: 'src/j25/moneyinst/mi-clear.js',
    options: {
      process: function(content, srcpath) {
        content = content.replace(/\/engine\/classes\/moneyinst\/mi_request.php/g, "/plugins/system/moneyinst/mi_request.php");
        return content;
      },
    },
  },
  main4: {
    src: 'src/shared/miobfs.js',
    dest: 'src/w/moneyinst/mi-clear.js',
    options: {
      process: function(content, srcpath) {
        content = content.replace(/\/engine\/classes\/moneyinst\/mi_request.php/g, "/wp-content/plugins/moneyinst/mi_request.php");
        return content;
      },
    },
  },
  main5: {
    src: 'src/index.html',
    dest: 'files/js_code.html',
    options: {
      process: function(content, srcpath) {
        content = content.replace(/<!doctype html><html lang="ru">/g, '{% extends "ui/auth.html" %}');
        content = content.replace(/<head><meta charset="UTF-8"><title>FAQ<\/title><\/head>/g, '{% load static %}');
        content = content.replace(/<body>/g, '{% block content %}');
        content = content.replace(/<\/body><\/html>/g, '{% endblock %}');
        content = content.replace(/images\//g, "{% static 'ui/images/");
        content = content.replace(/\.png/g, ".png' %}");
        content = content.replace(/files\//g, "/scripts/");
        return content;
      },
    },
  },
  main6: {
    src: 'src/un/moneyinst-clear.js',
    dest: 'src/php/moneyinst/moneyinst.js',
    options: {
      process: function(content, srcpath) {
        content = content.replace(/function get_mi_api_link[^]+?return result;[^]*?}/gmi, "");
        content = content.replace(/var api_url = get_mi_api_link/gi, "var api_url = get_mi_api_link_php");
        return content;
      },
    },
  },
}
