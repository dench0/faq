module.exports = function(grunt) {

  function make_file_name() {
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyz";
    for( var i=0; i < 8; i++ ) {
      text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
  }

  rnd_dir = make_file_name();
  rnd_php = make_file_name() + '.php';
  rnd_js = make_file_name() + '.js';
  rnd_dir_js = rnd_dir + '/' + rnd_js;
  rnd_dir_php = rnd_dir + '/' + rnd_php;

  grunt.loadNpmTasks('js-obfuscator');
  require('load-grunt-config')(grunt);
};
