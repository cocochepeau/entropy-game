<?php
class App {

	public $game = null;

	public function __construct() {
		// game starter
		if(isset($_POST['start'])) {
			$this->startGame(
				trim($_POST['playerOne']),
				trim($_POST['playerTwo'])
			);
		}

		// game restarter
		if(isset($_POST['restart'])) {
			$this->restartGame();
		}

		// retrieve game from session
		$this->retrieveGame();

		// if game is being played right now...
		if($this->game instanceOf Game) {

			// todo: isAlone()

			$this->game->possibleMovement(
				$_GET['x'],
				$_GET['y'],
				$_GET['p']
			);

			if(isset($_GET['move'])) {
				$this->game->deplacer(
					$_GET['x'],
					$_GET['y'],
					$_GET['p']
				);
			}
		}
	}

	public function startGame() {
		if(!Session::get('game')) {
			$playerOneName = trim($_POST['playerOne']);
			$playerTwoName = trim($_POST['playerTwo']);

			if($playerOneName != '' && $playerTwoName != '') {
				$this->game = new Game($playerOneName, $playerTwoName);
				Session::set('game', $this->game);
			}
		}
	}

	public function restartGame() {
		Session::destroy();
	}

	public function getGame() {
		if($this->game instanceOf Game) {
			return $this->game;
		}
		return false;
	}

	public function retrieveGame() {
		$game = Session::get('game');
		$game = $_SESSION['game'];
		if($game) {
			$this->game = $game;
			return true;
		}
		return false;
	}

}
