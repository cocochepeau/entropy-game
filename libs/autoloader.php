<?php
// Basic autoloader
spl_autoload_register(function ($class) {

	// path
	$path = __DIR__ . '/src/'.$class.'.class.php';

	// require once if exists
	if(file_exists($path)) {
		require_once($path);
	}
	

});
