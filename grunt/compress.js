module.exports = {
  main1: {
    options: {
      archive: 'files/dle_utf.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/du/',
      src: ['**'],
      dot: true
    }]
  },
  main2: {
    options: {
      archive: 'files/dle_win1251.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/dw/',
      src: ['**'],
      dot: true
    }]
  },
  main3: {
    options: {
      archive: 'files/moneyinst_wp.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/w/',
      src: ['**']
    }]
  },
  main4: {
    options: {
      archive: 'files/j/moneyinst.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/j/',
      src: ['**']
    }]
  },
  main5: {
    options: {
      archive: 'files/j25/moneyinst.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/j25/',
      src: ['**']
    }]
  },
  main6: {
    options: {
      archive: 'files/moneyinst.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/un/',
      src: ['moneyinst.js']
    }]
  },
  main7: {
    options: {
      archive: 'files/php/moneyinst.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/php/',
      src: ['**'],
      dot: true
    }]
  },
  main8: {
    options: {
      archive: 'files/php-clear/moneyinst.zip'
    },
    files: [{
      expand: true,
      cwd: 'src/php-clear/',
      src:'**'
    }]
  },
}
