<?php

define('IS_DEBUG', true);

define('LOG_DIR', 'log/');

ini_set('error_log', LOG_DIR . date('Ymd') . '.log');
ini_set('display_errors', false);
ini_set('log_errors', true);
error_reporting(E_ALL & ~E_NOTICE);
