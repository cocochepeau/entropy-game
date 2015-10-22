<?php
class App {

	public static $game = null;

	public static function init() {
		// game starter
		if(isset($_POST['start'])) {
			if(!Session::get('game')) {
				$playerOneName = trim($_POST['playerOne']);
				$playerTwoName = trim($_POST['playerTwo']);

				if($playerOneName != '' && $playerTwoName != '') {
					self::$game = new Game($playerOneName, $playerTwoName);
					Session::set('game', self::$game);
				}
			}
		}

		// game restarter
		if(isset($_POST['restart'])) {
			Session::destroy();
		}

		// if a game is being played
		if(Session::get('game')) {
			// ...
		}
	}

	public static function getGame() {
		$game = Session::get('game');
		if($game) return $game;
		return false;
	}

}
