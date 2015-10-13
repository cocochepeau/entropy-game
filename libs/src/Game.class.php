<?php
class Game {
	private $playerOne;
	private $playerTwo;
	private $board;
	
	public function __construct($playerNameOne, $playerNameTwo) {
		$this->playerOne = new Player(1, $playerNameOne);
		$this->playerTwo = new Player(2, $playerNameTwo);

		// initialisation of board	
		$this->board = array();
		$this->board[0] = array(new Pawn($playerOne), new Pawn($playerOne), new Pawn($playerOne), new Pawn($playerOne), new Pawn($playerOne));
		$this->board[1] = array(new Pawn($playerOne), null, null, null, new Pawn($playerOne));
		$this->board[2] = array(null, null, null, null, null);
		$this->board[3] = array(new Pawn($playerTwo), null, null, null, new Pawn($playerTwo));
		$this->board[4] = array(new Pawn($playerOne), new Pawn($playerTwo), new Pawn($playerTwo), new Pawn($playerTwo), new Pawn($playerTwo));
	}

	public function possibleMovement($targetedPawn){

	}

	public function endGame() {
		if($this->playerOne->cantMove()	|| $this->playerTwo->cantMove()) {
			
		}
	}
}
