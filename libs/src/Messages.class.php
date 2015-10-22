<?php
class Messages {

	private static $messages = array();

	public static function set($key, $value) {
		self::$messages[$key] = $value;
	}

	public static function add($key, $value) {
		$oldValue = self::get($key);
		if($oldValue) {
			self::$messages[$key] = $oldValue.$value;
		} else {
			self::$messages[$key] = $value;
		}
	}

	public static function get($key) {
		if(!empty(self::$messages)) {
			return self::$messages[$key];
		}
		return false;
	}
}
