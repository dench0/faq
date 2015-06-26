module.exports = {
  dle_php_js: {
    src: ['tmp/du/engine/modules/moneyinst.php', 'tmp/dw/engine/modules/moneyinst.php'],
    overwrite: true,
    replacements: [{
      from: 'engine/classes/moneyinst/miobfs.js',
      to: 'engine/classes/' + rnd_dir_js
    }]
  },
  dle_php_un1: {
    src: ['tmp/du/engine/inc/moneyinst.php', 'tmp/dw/engine/inc/moneyinst.php'],
    overwrite: true,
    replacements: [{
      from: 'classes/moneyinst/miobfs.js',
      to: 'classes/' + rnd_dir_js
    }]
  },
  dle_php_un2: {
    src: ['tmp/du/engine/inc/moneyinst.php', 'tmp/dw/engine/inc/moneyinst.php'],
    overwrite: true,
    replacements: [{
      from: 'classes/moneyinst/mi_request.php',
      to: 'classes/' + rnd_dir_php
    }]
  },
  dle_php_un3: {
    src: ['tmp/du/engine/inc/moneyinst.php', 'tmp/dw/engine/inc/moneyinst.php'],
    overwrite: true,
    replacements: [{
      from: 'classes/moneyinst/.htaccess',
      to: 'classes/' + rnd_dir + '/.htaccess'
    }]
  },
  dle_php_un4: {
    src: ['tmp/du/engine/inc/moneyinst.php', 'tmp/dw/engine/inc/moneyinst.php'],
    overwrite: true,
    replacements: [{
      from: 'classes/moneyinst/',
      to: 'classes/' + rnd_dir + '/'
    }]
  },
  dle_js_php: {
    src: ['tmp/du/engine/classes/moneyinst/miobfs.js', 'tmp/dw/engine/classes/moneyinst/miobfs.js'],
    overwrite: true,
    replacements: [{
      from: '/engine/classes/moneyinst/mi_request.php',
      to: '/engine/classes/' + rnd_dir_php
    }]
  },
  dle_js_ht: {
    src: ['tmp/du/engine/classes/moneyinst/.htaccess', 'tmp/dw/engine/classes/moneyinst/.htaccess', 'tmp/php/moneyinst/.htaccess'],
    overwrite: true,
    replacements: [{
      from: 'mi_request\\.php|miobfs\\.js',
      to: rnd_php.replace('.', '\\.') + '|' +  rnd_js.replace('.', '\\.')
    }]
  },
  w_php_js: {
    src: ['tmp/w/moneyinst/moneyinst.php'],
    overwrite: true,
    replacements: [{
      from: 'moneyinst/miobfs.js',
      to: rnd_dir_js
    }]
  },
  w_js_php: {
    src: ['tmp/w/moneyinst/miobfs.js'],
    overwrite: true,
    replacements: [{
      from: '/engine/classes/moneyinst/mi_request.php',
      to: '/wp-content/plugins/' + rnd_dir_php
    }]
  },
  php_js_php: {
    src: ['tmp/php/moneyinst/miobfs.js'],
    overwrite: true,
    replacements: [{
      from: 'moneyinst/mi_request.php',
      to: rnd_dir_php
    }]
  },
  j_js_php: {
    src: ['tmp/j/moneyinst/miobfs.js', 'tmp/j25/moneyinst/miobfs.js'],
    overwrite: true,
    replacements: [{
      from: '/engine/classes/moneyinst/mi_request.php',
      to: '/plugins/system/' + rnd_dir_php
    }]
  },
}