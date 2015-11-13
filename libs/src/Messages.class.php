<?php
class Messages {

	private static $messages = array();

	public static function add($value) {
		self::$messages[] = $value;
	}

	public static function render() {
		$render = '';
		foreach(self::$messages as $message) {
			$render .= '<div>'.$message.'</div>';
		}
		echo $render;
	}
}
