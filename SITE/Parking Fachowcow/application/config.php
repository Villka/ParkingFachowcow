<?php

/* Settings of PDO driver and Database Host */

define('DB_DRV_SQLITE',         'sqlite');
define('DB_PATH',               'app/opt/admin.db');

define('PHP_OLD_VERSION',       false);
define('SERV_CROSS_DOMAIN',     true); //switch to false if server and client at the same domain or for build project
define('PATH_DIR_QUANTITY',     0); // quantity of folders from root (ex: /misza/newhd/). Max size == 3

define('VIEW_TYPE_DEFAULT',     '.json');

define('TY_PAGE_ENABLE',        true); // set true to enable thankyou page or false to disable
define('TY_PAGE_PATH',          'http://parkingfachowcow.eu/ok/'); // full path to thankyou page

/* =============================== */

define('REQ_SUCCESS_RU',        "Спасибо за заявку!\nНаш менеджер свяжется с вами в ближайшее время!\n");
define('REQ_ERROR_RU',          "Ошибка! Заявка не была отправлена! Попробуйте позже");
define('REQ_ERROR_VALID_RU',    "Ошибка! Заявка не была отправлена! Проверьте заполнение полей!");

define('REQ_SUCCESS_UA',        "Дякуємо за заявку!\nНаш менеджер зв'яжеться з вами найближчим часом!\n");
define('REQ_ERROR_UA',          "Помилка! Заявка не була відправлена! Спробуйте пізніше");
define('REQ_ERROR_VALID_UA',    "Ошибка! Заявка не была отправлена! Проверьте заполнение полей!");

define('REQ_SUCCESS_PL',        "Dziękujemy za zgłoszenie!\nNasz menedżer skontaktuje się z Tobą wkrótce!\n");
define('REQ_ERROR_PL',          "Błąd! Aplikacja nie została wysłana! Spróbuj ponownie później");
define('REQ_ERROR_VALID_PL',    "Błąd! Aplikacja nie została wysłana. Sprawdź pola!");

define('REQ_SUCCESS_EN',        "Thanks for the application!\nOur manager will contact you soon!\n");
define('REQ_ERROR_EN',          "Error! Application has not been sent! Try again later");
define('REQ_ERROR_VALID_EN',    "Error! Application has not been sent. Check the filling of the fields!");

/* =============================== */

define('REQ_SUBJECT',           'Заявка с сайта ' . $_SERVER['HTTP_HOST']);
define('REQ_WHO_MAILED',        '-f mail@parkingfachowcow.eu');


define('ERR_REQUEST_VALID',     'Пожалуйста, заполните поля верно!');

/* Error messages for PDOHandler */
define('ERR_CONNECT_DB',        "Cannot connect database. Check username or password");
define('ERR_VAL_COLUMNS',       "Value of colums must be string or array type!");
define('ERR_VAL_TABLENAME',     "Value of table name must be string type!");
define('ERR_VAL_CONDITIONS',    "Conditions must be a string type!");
define('ERR_VAL_LIMIT',         "Limit must be a numeric type!");
define('ERR_VAL_VALUES',        "Values must be string or array type!");
define('ERR_VAL_UPDATE',        "Columns and Values must be array type!");

/* Error messages for Validator */
define('ERR_STRING_LIMIT',      "String's length more then limit");
define('ERR_STRING_TYPE',       "Recieved value is not string or string is empty");
define('ERR_STRING_CHARS',      "Recieved value is not string");
define('ERR_EMAIL_FILTER',      "Email is not correct");
define('ERR_INT_NULL',          "Recieved data is null");
define('ERR_INT_NOT_NUMERIC',   "Recieved data is not numeric");
define('ERR_PASS_SPACES',       "Password contain spaces at beginning or end of the string");