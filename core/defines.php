<?php
if (defined('LOCALIZER') || isset($ajaxcall)) {
// Global definitions
define('PATH_BASE', dirname(__DIR__));
$parts = explode(DIRECTORY_SEPARATOR, PATH_BASE); // PATH BASE DEFINES IN DIFFERENT PLACES!

// Defines.
define('PATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));  // DIRECTORY_SEPARATOR is the constant that contain / or \ 
define('PATH_SITE',          PATH_ROOT);
define('PATH_CONFIGURATION', PATH_ROOT);
define('PATH_CORE',          PATH_ROOT .          DIRECTORY_SEPARATOR . 'core');
define('PATH_CONFIG',        PATH_ROOT .          DIRECTORY_SEPARATOR . "connect" . DIRECTORY_SEPARATOR . "mainconfig.php");
define('PATH_DBCONNECT',     PATH_CORE .          DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "dbconnect.php");
define('PATH_ADMINISTRATOR', PATH_ROOT .          DIRECTORY_SEPARATOR . 'administrator');
define('PATH_LIBRARIES',     PATH_ROOT .          DIRECTORY_SEPARATOR . 'libraries');
define('PATH_LIBRARIES_FUNC',PATH_LIBRARIES .     DIRECTORY_SEPARATOR . 'functions');
define('PATH_PLUGINS',       PATH_ROOT .          DIRECTORY_SEPARATOR . 'plugins');
define('PATH_LANGUAGE',      PATH_ROOT .          DIRECTORY_SEPARATOR . 'localization');
define('PATH_TEMPLATES',     PATH_BASE .          DIRECTORY_SEPARATOR . 'template');
define('PATH_CACHE',         PATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'cache');
define('PATH_COOKIES',       PATH_CORE .          DIRECTORY_SEPARATOR . 'cookie');
define('PATH_API',           PATH_ROOT .          DIRECTORY_SEPARATOR . 'api');
define('PATH_CLI',           PATH_ROOT .          DIRECTORY_SEPARATOR . 'cli');
define('PATH_AUTH',          PATH_CORE .          DIRECTORY_SEPARATOR . 'auth');
define('PATH_COMPONENTS',    PATH_ROOT .          DIRECTORY_SEPARATOR . 'components');
define('PATH_CLASS',         PATH_LIBRARIES .     DIRECTORY_SEPARATOR . 'classes');

define('USERS_FOLDER',  'users');
define('AVATAR_FOLDER', 'avatars');
define('DIARY_FOLDER',  'diaryposts');
define('NOTE_FOLDER',   'noteposts');

if (!defined('DS')) {
define('DS', DIRECTORY_SEPARATOR);
};

} else {
  die;
}