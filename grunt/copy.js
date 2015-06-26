module.exports = {
  allfiles: {
    files: [{
      cwd: 'src/',
      src: '**',
      dest: 'tmp/',
      expand: true,
      dot: true,
    }, {
      src: 'tmp/shared/mi_request.php',
      dest: 'tmp/du/engine/classes/moneyinst/mi_request.php'
    }, {
      src: 'tmp/shared/mi_request.php',
      dest: 'tmp/dw/engine/classes/moneyinst/mi_request.php'
    }, {
      src: 'tmp/shared/mi_request.php',
      dest: 'tmp/j/moneyinst/mi_request.php'
    }, {
      src: 'tmp/shared/mi_request.php',
      dest: 'tmp/j25/moneyinst/mi_request.php'
    }, {
      src: 'tmp/shared/mi_request.php',
      dest: 'tmp/w/moneyinst/mi_request.php'
    }, {
      src: 'tmp/shared/miobfs.js',
      dest: 'tmp/dw/engine/classes/moneyinst/miobfs.js'
    }, {
      src: 'tmp/shared/miobfs.js',
      dest: 'tmp/du/engine/classes/moneyinst/miobfs.js'
    }, {
      src: 'tmp/shared/.htaccess',
      dest: 'tmp/du/engine/classes/moneyinst/.htaccess'
    }, {
      src: 'tmp/shared/.htaccess',
      dest: 'tmp/dw/engine/classes/moneyinst/.htaccess'
    }, {
      src: 'tmp/shared/mi_request.php',
      dest: 'tmp/php/moneyinst/mi_request.php'
    }, {
      src: 'tmp/shared/.htaccess',
      dest: 'tmp/php/moneyinst/.htaccess'
    },  {
      src: 'tmp/un/moneyinst-clear.js',
      dest: 'tmp/php/moneyinst/miobfs.js',
    }, {
      src: 'tmp/shared/miobfs.js',
      dest: 'tmp/j/moneyinst/miobfs.js',
    }, {
      src: 'tmp/shared/miobfs.js',
      dest: 'tmp/j25/moneyinst/miobfs.js',
    }, {
      src: 'tmp/shared/miobfs.js',
      dest: 'tmp/w/moneyinst/miobfs.js',
    }, {
      cwd: 'tmp/images/',
      src: '**',
      dest: 'files/images/',
      expand: true
    }, ]
  },
  htmlcode: {
    src: 'tmp/index.html',
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
  dev: {
    files: [{
      src: 'tmp/un/moneyinst-clear.js',
      dest: 'tmp/un/miobfs.js'
    },
    {
      src: 'tmp/un/moneyinst-clear.js',
      dest: 'files/ucoz.js'
    }]
  }
}
