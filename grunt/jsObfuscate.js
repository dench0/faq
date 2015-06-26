module.exports = {
  options: {
    concurrency: 2,
    keepLinefeeds: false,
    keepIndentations: false,
    encodeStrings: true,
    encodeNumbers: true,
    moveStrings: true,
    replaceNames: true,
    variableExclusions: ['^_get_', '^_set_', '^_mtd_']
  },
  test1: {
    files: {
      'tmp/du/engine/classes/moneyinst/miobfs.js': ['tmp/du/engine/classes/moneyinst/miobfs.js']
    }
  },
  test2: {
    files: {
      'tmp/dw/engine/classes/moneyinst/miobfs.js': ['tmp/dw/engine/classes/moneyinst/miobfs.js']
    }
  },
  test3: {
    files: {
      'files/ucoz.js': ['files/ucoz.js']
    }
  },
  test4: {
    files: {
      'tmp/un/miobfs.js': ['tmp/un/miobfs.js']
    }
  },
  test5: {
    files: {
      'tmp/j/moneyinst/miobfs.js': ['tmp/j/moneyinst/miobfs.js']
    }
  },
  test6: {
    files: {
      'tmp/j25/moneyinst/miobfs.js': ['tmp/j25/moneyinst/miobfs.js']
    }
  },
  test7: {
    files: {
      'tmp/w/moneyinst/miobfs.js': ['tmp/w/moneyinst/miobfs.js']
    }
  },
  test8: {
    files: {
      'tmp/php/moneyinst/miobfs.js': ['tmp/php/moneyinst/miobfs.js']
    }
  },
}
