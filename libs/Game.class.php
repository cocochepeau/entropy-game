<?php
class Game
{
	private $playerOne;
	private $playerTwo;
	private $board;
	
	public function __construct($playerOne, $playerTwo)
	{
		
	}

	public function endGame()
	{
		if(
			$this->playerOne->cantMove()
			|| $this->playerTwo->cantMove()
		)
	}
}
