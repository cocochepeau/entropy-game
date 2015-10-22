<?php
class Session {

	public static $sessionName = 'eg_sess';
	public static $lifetime = 604800; // 604800 = 7 days
	public static $path = '/';
	public static $domain = '';
	public static $secure = false;
	public static $HTTPOnly = true;

	public static function init($sessionName = 'eg_sess') {
		self::$sessionName = $sessionName;

		session_name(self::$sessionName);
		session_set_cookie_params(
			self::$lifetime,
			self::$path,
			self::$domain,
			self::$secure,
			self::$HTTPOnly
		);

		@session_start();
	}

	// setters
	public static function setLifetime($lifetime) {
		self::$lifetime = $lifetime;
	}

	public static function setPath($path) {
		self::$path = $path;
	}

	public static function setDomain($domain) {
		self::$domain = $domain;
	}

	public static function setSecure($secure) {
		self::$secure = $secure;
	}

	public static function setHTTPOnly($HTTPOnly) {
		self::$HTTPOnly = $HTTPOnly;
	}

	public static function set($key, $value) {
		if($key != '' && $value != '') {
			$_SESSION[$key] = $value;
			return true;
		}
		return false;
	}

	// getters
	public static function getSessionName() {
		return self::$sessionName;
	}

	public static function get($key) {
		if(isset($_SESSION[$key])) return $_SESSION[$key];
		return false;
	}

	public static function destroy($init = true) {
		$_SESSION = array();
		session_destroy();
		if($init) self::init(self::$sessionName);
		return true;
	}
}
