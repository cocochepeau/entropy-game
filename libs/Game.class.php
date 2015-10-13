<?php
class Game
{
	private $playerOne;
	private $playerTwo;
	private $board;
	
	public function __construct($playerNameOne, $playerNameTwo) {
		$playerOne = new Player($playerNameOne);
		$playerTwo = new Player($playerNameTwo);

	//initialisation of board	
		$board =array();
		$board[1] = array(new Pawn($playerOne), new Pawn($playerOne), new Pawn($playerOne), new Pawn($playerOne), new Pawn($playerOne));
		$board[2] = array(new Pawn($playerOne), null, null, null, new Pawn($playerOne));
		$board[3] = array(null, null, null, null, null);
		$board[4] = array(new Pawn($playerTwo), null, null, null, new Pawn($playerTwo));
		$board[5] = array(new Pawn($playerOne), new Pawn($playerTwo), new Pawn($playerTwo), new Pawn($playerTwo), new Pawn($playerTwo));
	}

	public function endGame() {
		if(
			$this->playerOne->cantMove()
			|| $this->playerTwo->cantMove()
		)
	}
}
