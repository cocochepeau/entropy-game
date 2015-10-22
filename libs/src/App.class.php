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

		// retreive game
		if(!self::$game instanceOf Game) {
			$game = Session::get('game');
			if($game) {
				self::$game = $game;
			}
		}

		// if game is being played right now...
		if(self::$game instanceOf Game) {
			// tests
			self::$game->possibleMovement($_GET['x'], $_GET['y'], $_GET['p']);
		}
		
	}

	public static function getGame() {
		$game = Session::get('game');
		if($game) return $game;
		return false;
	}

}
