const replace = require('replace-in-file');
const browserSync = require('browser-sync').create();
const dotenv = require('dotenv');

dotenv.config();
const proxy_host = (process.platform == 'win32') ?'192.168.99.101': 'localhost';

exports.fix_script =  function (done) {
  replace.sync({
    files: 'database/model/script.sql',
    from: [
      /USE `GrandChef`\$\$\r?\n/igm,
      /USE `GrandChef`;\r?\n/igm,
      /END\$\$\r?\n/igm,
      /`GrandChef`\./igm
    ],
    to: [
      '',
      '',
      'END $$$$',
      ''
    ],
  });
  replace.sync({
    files: 'database/model/sqlite.sql',
    from: [
      /ATTACH "GrandChef.sdb" AS "GrandChef";\r?\nBEGIN;\r?\n/igm,
      /"GrandChef"."/igm,
      /COMMIT;/igm
    ],
    to: [
      '',
      '"',
      '\nPRAGMA foreign_keys = ON;'
    ],
  });
  done();
};

exports.fix_sql = function (done) {
  replace.sync({
    files: 'storage/db/dumps/script_no_trigger.sql',
    from: [
      /\r?\nDELIMITER \$\$[\s\S]*(?=DELIMITER ;)DELIMITER ;\r?\n\r?\n/igm,
      /' \/\* comment truncated \*\/ \/\*([^\*]+)\*\//igm,
      /([^\\][\\])([^\\'])/igm
    ],
    to: [
      '',
      '$1\'',
      '$1\\$2'
    ],
  });
  done();
};

exports.fix_pop = function (done) {
  let dbname = 'mydb'
  let i = process.argv.indexOf('--name');
  if (i > -1) {
    dbname = process.argv[i + 1];
  }
  replace.sync({
    files: 'storage/db/dumps/populate.sql',
    from: /`GrandChef`/igm,
    to: '`' + dbname + '`'
  });
  done();
};

exports.default = function () {
  browserSync.init({
    ui: false,
    open: false,
    proxy: proxy_host + ':' + process.env.WEB_PORT,
    port: process.env.WEB_PORT - 5000
  });
};
