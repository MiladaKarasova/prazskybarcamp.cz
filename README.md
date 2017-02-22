# PrazskyBarcamp.cz

| Master  | Develop |
| :------ | :------ |
| [![StyleCI](https://styleci.io/repos/79055930/shield?branch=master)](https://styleci.io/repos/79055930) | [![StyleCI](https://styleci.io/repos/79055930/shield?branch=develop)](https://styleci.io/repos/79055930) |
| [![Codacy](https://api.codacy.com/project/badge/Grade/0a91176118544eb4906475b275fa9ad9)](https://www.codacy.com/app/vojtasvoboda/prazskybarcamp-cz) | [![Codacy](https://api.codacy.com/project/badge/Grade/0a91176118544eb4906475b275fa9ad9)](https://www.codacy.com/app/vojtasvoboda/prazskybarcamp-cz) |
| [![Code Coverage](https://scrutinizer-ci.com/g/BarcampPraha/prazskybarcamp.cz/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/BarcampPraha/prazskybarcamp.cz/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/BarcampPraha/prazskybarcamp.cz/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/BarcampPraha/prazskybarcamp.cz/?branch=develop) |
| [![Build Status](https://travis-ci.org/BarcampPraha/prazskybarcamp.cz.svg?branch=master)](https://travis-ci.org/BarcampPraha/prazskybarcamp.cz) | [![Build Status](https://travis-ci.org/BarcampPraha/prazskybarcamp.cz.svg?branch=develop)](https://travis-ci.org/BarcampPraha/prazskybarcamp.cz) |
| [![Coverage Status](https://coveralls.io/repos/github/BarcampPraha/prazskybarcamp.cz/badge.svg?branch=master)](https://coveralls.io/github/BarcampPraha/prazskybarcamp.cz?branch=master) | [![Coverage Status](https://coveralls.io/repos/github/BarcampPraha/prazskybarcamp.cz/badge.svg?branch=develop)](https://coveralls.io/github/BarcampPraha/prazskybarcamp.cz?branch=develop) |

www.prazskybarcamp.cz

## Installation

1. Create .env file as copy of .env.dist and fill each line. Don't forget to fill encryption key and cipher!
2. Run `composer install`.
3. Create file `/config/dev/app.php` with content `<?php return ['debug' => true];` to set up debug mode.
4. Create database and run database migrations by `php artisan october:up`.

## Run on localhost

1. Run local server by `php artisan serve`. Project should works at `http://localhost:8000/`.
2. Login to backend at `http://localhost:8000/backend` with admin/admin credentials.
3. For developing theme check another readme file at theme folder.

## Run on production

1. Set environment to production at .env file (APP_ENV=production).
2. Set Error Logger plugin at backend.
3. Remove admin account and create own.
4. Set mailing at Backend > Mail configuration.
5. Set mail templates text at Backend > Mail templates
    - **rainlab.user::mail.activate** is sent after registration
    - **rainlab.user::mail.welcome** after successfull account confirmation

## Requirements

- PHP 5.5.9.
- MySQL 5.7.x or MariaDb.

## Unit tests

Just run `phpunit`.

In case of "Class not found" error try to run `composer dump-autoload` for regenerate autoload files.

## Contribution

Feel free to send pull-request to the master branch.
