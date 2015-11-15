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
		if($this->game != null) { // instanceOf Game

			// want to cancel your move ?
			if(isset($_POST['previous'])) {
				$this->game->cancelMove();
			}

			// looking for isolated & blocked pawns
			$this->game->scanPawns();

			// do you want to select a pawn in order to move it ?
			if(isset($_GET['select'])) {
			/*	if(!empty($this->game->alonePawns)){
					
				}else{*/
				$this->game->availableMovements(
					$_GET['x'],
					$_GET['y'],
					$_GET['p']
				);
				}
			}

			// let's move it move it !
			if(isset($_GET['move'])) {
				$this->game->move(
					$_GET['srcX'],
					$_GET['srcY'],
					$_GET['destX'],
					$_GET['destY']
				);
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
		if($this->game != null) { // instanceOf Game
			return $this->game;
		}
		return false;
	}

	public function retrieveGame() {
		$game = Session::get('game');
		if($game) {
			$this->game = $game;
			return true;
		}
		return false;
	}

}
