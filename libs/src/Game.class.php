<?php
class Game {

	private $playerOne;
	private $playerTwo;
	private $board;

	/*
	 * array to list all pawns which can't move,
	 * it's quick to check in this array if the game is ended.
	 */
	private $staticPawnYellow = array();
	private $staticPawnBlue = array();

	public function __construct($playerNameOne, $playerNameTwo) {
		// init players
		$this->playerOne = new Player(1, $playerNameOne);
		$this->playerTwo = new Player(2, $playerNameTwo);

		// init board	
		$this->board = array();
		$this->board[0] = array(new Pawn($this->playerOne), new Pawn($this->playerOne), new Pawn($this->playerOne), new Pawn($this->playerOne), new Pawn($this->playerOne));
		$this->board[1] = array(new Pawn($this->playerOne), null, null, null, new Pawn($this->playerOne));
		$this->board[2] = array(null, null, null, null, null);
		$this->board[3] = array(new Pawn($this->playerTwo), null, null, null, new Pawn($this->playerTwo));
		$this->board[4] = array(new Pawn($this->playerTwo), new Pawn($this->playerTwo), new Pawn($this->playerTwo), new Pawn($this->playerTwo), new Pawn($this->playerTwo));
	}

	public function getPlayerOne() {
		return $this->playerOne;
	}

	public function getPlayerTwo() {
		return $this->playerTwo;
	}

	// return an array of coordinate
	public function possibleMovement() {
		// $player = (int)$_GET['p'];
		$x = (int)$_GET['x'];
		$y = (int)$_GET['y'];

		$possibleMoves = array();
		$possibleMoves[] = $this->possibleHorizontalMovement($x, $y);
		$possibleMoves[] = $this->possibleVerticalMovement($x, $y);
		$possibleMoves[] = $this->possibleDiagonalMovement($x, $y);

		// looking for blocked pawns
		if(
			empty($possibleCoord[0]) // horizontal
			&& empty($possibleCoord[1]) // vertical
			&& empty($possibleCoord[2]) // diagonal
		) {
			if($this->board[$x][$y]->getColor() == 'yellow') {
				array_push($this->staticPawnYellow, $this->board[$x][$y]);
			} else {
				array_push($this->staticPawnBlue, $this->board[$x][$y]);
			}
		}
		return $possibleMoves;
	}

	public function possibleHorizontalMovement($x, $y) {
		// return an array of coordinate
		// array struct array[n][0] = $CoordX   and array[n][1] = $CoordY
		$possibleCoord = array();
		if($x = 0) {
			// checking on the right side
			$cpt = $x + 1;
			$cursorBox = $this->board[$cpt][$y];
			while(($cursorBox != null) && ($cpt <= 4)) {
				array_push($possibleCoord, $cpt, $y);
				$cpt++;
				$cursorBox = $this->board[$cpt][$y];
			}
		} else {
			if($x == 4) {
				// checking on the left side
				$cpt = $x - 1;
				$cursorBox = $this->board[$cpt][$y];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($cursorBox, $cpt,$y);
					$cpt--;
					$cursorBox = $this->board[$cpt][$y];
				}
			} else {
				// checking on the right side
				$cpt = $x + 1;
				$cursorBox= $this->board[$cpt][$y];
				while(($cursorBox != null) && ($cpt <= 4)) {
					array_push($cursorBox, $cpt, $y);
					$cpt++;
					$cursorBox = $this->board[$cpt][$y];
				}

				// checking on the left side
				$cpt = $x - 1;
				$cursorBox = $this->board[$cpt][$y];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($cursorBox, $cpt,$y);
					$cpt--;
					$cursorBox = $this->board[$cpt][$y];
				}
			}
		}
		return $possibleCoord;
	}

	public function possibleVerticalMovement($x, $y) {
		// return an array of coordinate
		// array struct array[n][0] = $CoordX   and array[n][1] = $CoordY
		$possibleCoord = array();
		if($y = 0) {
			// checking on the right side
			$cpt = $y + 1;
			$cursorBox = $this->board[$x][$cpt];
			while(($cursorBox != null) && ($cpt <= 4)) {
				array_push($possibleCoord, $x, $cpt);
				$cpt++;
				$cursorBox = $this->board[$x][$cpt];
			}
		} else {
			if($y == 4) {
				// checking on the left side
				$cpt = $y - 1;
				$cursorBox = $this->board[$x][$cpt];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($possibleCoord, $x, $cpt);
					$cpt--;
					$cursorBox = $this->board[$x][$cpt];
				}
			} else {
				// checking on the right side
				$cpt = $y + 1;
				$cursorBox= $this->board[$x][$cpt];
				while(($cursorBox != null) && ($cpt <= 4)) {
					array_push($possibleCoord, $x, $cpt);
					$cpt++;
					$cursorBox = $this->board[$x][$cpt];
				}

				// checking on the left side
				$cpt = $y - 1;
				$cursorBox = $this->board[$x][$cpt];
				while(($cursorBox != null) && ($cpt >= 0)) {
					array_push($possibleCoord, $x, $cpt);
					$cpt--;
					$cursorBox = $this->board[$x][$cpt];
				}
			}
		}
		return $possibleCoord;
	}

	public function possibleDiagonalMovement($x, $y) {
		// array struct array[n][0] = $CoordX   and array[n][1] = $CoordY
		$possibleCoord = new array();
		$cptX = $x - 1;
		$cptY = $y + 1;
		// checking bottom/left side
		if(($cptX >= 0) && ($cptY <= 4)){ // is to avoid an nuller pointer error on the board's array.
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX >= 0) && ($cptY <= 4)) && $cursorBox != null){
				array_push($possibleCoord, $cptX, $cptY);
				$cptX--;
				$cptY++;
				$cursorBox = $this->board[$cptX][$cptY];
			}
		}
		// checking top/right side
		$cptX = $x + 1;
		$cptY = $y - 1;
		if(($cptX <= 4) && ($cptY >= 0)){
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX <= 4) && ($cptY >= 0)) && $cursorBox != null){
				array_push($possibleCoord, $cptX, $cptY);
				$cptX++;
				$cptY--;
				$cursorBox = $this->board[$cptX][$cptY];
			}
		}
		// checking bottom/right side
		$cptX = $x + 1;
		$cptY = $y + 1;
		if(($cptX <= 4) && ($cptY <= 4)){
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX <= 4) && ($cptY <= 4)) && $cursorBox != null){
				array_push($possibleCoord, $cptX, $cptY);
				$cptX++;
				$cptY++;
				$cursorBox = $this->board[$cptX][$cptY];
			}
		}
		// checking top/left side
		$cptX = $x - 1;
		$cptY = $y - 1;
		if(($cptX >= 0) && ($cptY >= 0)){
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX >= 0) && ($cptY >= 0)) && $cursorBox != null){
				array_push($possibleCoord, $cptX, $cptY);
				$cptX--;
				$cptY--;
				$cursorBox = $this->board[$cptX][$cptY];
			}
		}
		
		return $possibleCoord;
	}

	public function endGame() {
		return ((count($this->$staticPawnYelow) == 5) || (count($this->$staticPawnBlue) == 5));
	}

	public function drawBoard() {
		$render = '<table><tbody>';

		$y = 0;
		foreach($this->board as $row) {
			$x = 0;
			$render .= '<tr>';
			foreach($row as $col) {
				if($col != null) {
					$render .= '<td><div class="box"><a href="'.ROOT.'/index.php?p='.$col->getPlayer()->getNumPlayer().'&x='.$x.'&y='.$y.'" class="pawn '.$col->getColor().'"></a></div></td>';
				} else {
					$render .= '<td><div class="box"></div></td>';
				}
				$x++;
			}
			$render .= '</tr>';
			$y++;
		}
		
		echo $render.'</tbody></table>';
	}

}
