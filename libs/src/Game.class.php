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
		$this->board[0] = array(new Pawn($this->playerOne), new Pawn($this->playerOne), new Pawn($this->playerOne), new Pawn($this->playerOne), new Pawn($this->playerOne));
		$this->board[1] = array(new Pawn($this->playerOne), null, null, null, new Pawn($this->playerOne));
		$this->board[2] = array(null, null, null, null, null);
		$this->board[3] = array(new Pawn($this->playerTwo), null, null, null, new Pawn($playerTwo));
		$this->board[4] = array(new Pawn($this->playerOne), new Pawn($this->playerTwo), new Pawn($this->playerTwo), new Pawn($this->playerTwo), new Pawn($this->playerTwo));
	}

	public function possibleMovement($targetedPawn){

	}

	public function endGame() {
		if($this->playerOne->cantMove()	|| $this->playerTwo->cantMove()) {
			
		}
	}
}
