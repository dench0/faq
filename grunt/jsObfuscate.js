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
      'src/du/engine/classes/moneyinst/miobfs.js': ['src/du/engine/classes/moneyinst/miobfs.js']
    }
  },
  test2: {
    files: {
      'src/dw/engine/classes/moneyinst/miobfs.js': ['src/dw/engine/classes/moneyinst/miobfs.js']
    }
  },
  test3: {
    files: {
      'src/uc/ucoz.js': ['src/uc/ucoz.js']
    }
  },
  test4: {
    files: {
      'src/un/moneyinst.js': ['src/un/moneyinst.js']
    }
  },
  test5: {
    files: {
      'src/j/moneyinst/mi-clear.js': ['src/j/moneyinst/mi-clear.js']
    }
  },
  test6: {
    files: {
      'src/j25/moneyinst/mi-clear.js': ['src/j25/moneyinst/mi-clear.js']
    }
  },
  test7: {
    files: {
      'src/w/moneyinst/mi-clear.js': ['src/w/moneyinst/mi-clear.js']
    }
  },
}
