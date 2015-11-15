<?php
// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

// defines
define('HE_FLAGS', ENT_QUOTES | ENT_HTML5);
define('CHARSET', 'UTF-8');

// autoloader
$autloader = __DIR__ . '/../libs/autoloader.php';
require_once($autloader);

// init session
Session::init();

// App
$app = new App();
$game = $app->getGame();
