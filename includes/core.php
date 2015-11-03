<?php
// Rapporte toutes les erreurs à part les E_NOTICE
// C'est la configuration par défaut de php.ini
error_reporting(E_ALL & ~E_NOTICE);

// defines
define('PROTOCOL', 'http');
define('SERV_ROOT', $_SERVER['DOCUMENT_ROOT'].'/entropy-game/');
define('SERVER_NAME', $_SERVER['SERVER_NAME']);
define('ROOT', PROTOCOL.'://'.SERVER_NAME.'/entropy-game');
define('URL', ROOT.$_SERVER['REQUEST_URI']);
define('URI', $_SERVER['REQUEST_URI']);
define('HE_FLAGS', ENT_QUOTES | ENT_HTML5);
define('CHARSET', 'UTF-8');

// autoloader
$autloader = SERV_ROOT . '/libs/autoloader.php';
require_once($autloader);

// init session
Session::init();

// App
$app = new App();
$game = $app->getGame();
