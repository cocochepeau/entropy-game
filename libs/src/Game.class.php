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
		$this->board[3] = array(new Pawn($this->playerTwo), null, null, null, new Pawn($this->playerTwo));
		$this->board[4] = array(new Pawn($this->playerOne), new Pawn($this->playerTwo), new Pawn($this->playerTwo), new Pawn($this->playerTwo), new Pawn($this->playerTwo));
	}

	public function possibleMovement() {
		//return an array of coordinate
			
		//TODO
		$x = (int) $_GET["x"];
		$y = (int) $_GET["y"];


	}

	public function possibleHorizontalMovement($x, $y) {
		//return an array of coordinate
		$possibleCoord = array();
		if($x = 0){
			//checking on the right side
			$cpt = $x+1;
			$cursorBox = $this->board[$cpt][$y];
			while(($cursorBox != null) && ($cpt <= 4)) {
				array_push($cursorBox, $this->board[$cpt][$y]);
				$cpt ++;
			}
		} else{
			if ($x == 4) {
				//checking on the left side
				$cpt = $x-1;
				$cursorBox = $this->board[$cpt][$y];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($cursorBox, $this->board[$cpt][$y]);
					$cpt --;
				}
			} else{
				//checking on the right side
				$cpt = $x+1;
				$cursorBox= $this->board[$cpt][$y];
				while(($cursorBox != null) && ($cpt <= 4)) {
					array_push($cursorBox, $this->board[$cpt][$y]);
					$cpt ++;
				}

				//checking on the left side
				$cpt = $x-1;
				$cursorBox = $this->board[$cpt][$y];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($cursorBox, $this->board[$cpt][$y]);
					$cpt --;
				}
			}
		}
		return $possibleCoord;
	}

	public function possibleVerticalMovement($x, $y){
		//return an array of coordinate
		$possibleCoord = array();
		$x = $targetedPawn->getCoordX();
		$y = $targetedPawn->getCoordY();
		if($y = 0){
			//checking on the right side
			$cpt = $y+1;
			$cursorBox = $this->board[$x][$cpt];
			while(($cursorBox != null) && ($cpt <= 4)) {
				array_push($cursorBox, $this->board[$x][$cpt]);
				$cpt ++;
			}
		} else{
			if ($y == 4) {
				//checking on the left side
				$cpt = $y-1;
				$cursorBox = $this->board[$x][$cpt];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($cursorBox, $this->board[$x][$cpt]);
					$cpt --;
				}
			} else{
				//checking on the right side
				$cpt = $y+1;
				$cursorBox= $this->board[$x][$cpt];
				while(($cursorBox != null) && ($cpt <= 4)) {
					array_push($cursorBox, $this->board[$x][$cpt]);
					$cpt ++;
				}

				//checking on the left side
				$cpt = $y-1;
				$cursorBox = $this->board[$x][$cpt];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($cursorBox, $this->board[$x][$cpt]);
					$cpt --;
				}
			}
		}
		return $possibleCoord;
	}

	public function possibleDiagonalMovement($targetedPawn){
			//TODO

		//checking bottom/left side

		//checking top/right side

		//checking bottom/right side

		//checking top/left side
	}

	public function endGame() {
		if($this->playerOne->cantMove()	|| $this->playerTwo->cantMove()) {
			
		}
	}
}
