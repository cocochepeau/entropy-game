 <?php
class Game {

	private $playerOne;
	private $playerTwo;
	private $board;
	private $whichTurn = 1; // 1 = playerOne
	private $coordPawnToMoveX;  //tmp coordinate of the pawn which have to move.
	private $coordPawnToMoveY;
	private $allowedMovement;
	
	/*
	 * array to list all pawns which can't move,
	 * it's quick to check in this array if the game is ended.
	 */
	private $staticPawns = array();

	public function __construct($playerNameOne, $playerNameTwo) {
		// init players
		$this->playerOne = new Player(1, $playerNameOne);
		$this->playerTwo = new Player(2, $playerNameTwo);

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

	public function deplacer($x, $y, $p) { //coordinate of destination
		// TODO
		//verif if the movement is possible for security
		$find = false;
		$cpt = 0;
		while(($find == false) && ($cpt <= (sizeof($this->allowedMovement) - 1))){
			if(($this->allowedMovement[$cpt]['x'] == $x) && ($this->allowedMovement[$cpt]['y'] == $y)){
				$find = true;
			} else{
				$cpt++;
			}
		}
		
	}

	// return an array of coordinate
	public function possibleMovement($x, $y, $p) {
		// sanitizing
		$x = (int)$x;
		$y = (int)$y;
		$p = (int)$p;

		if(($x >= 0 || $x <= 4) && ($y >= 0 || $y <= 4)	&& ($p == 1 || $p == 2)) {
			$moves = array();
			if($this->whichTurn == $p) {
				$moves['horizontal'] = $this->possibleHorizontalMovement($x, $y);
				// debug
				Messages::add('response', 'horizontal moves = {');
				foreach($moves['horizontal'] as $key => $value) {
					if($key >= count($moves['horizontal']) - 1) Messages::add('response', '('.$value['x'].','.$value['y'].')');
					else Messages::add('response', '('.$value['x'].','.$value['y'].'), ');
				}
				Messages::add('response', '}' . PHP_EOL);

				$moves['vertical'] = $this->possibleVerticalMovement($x, $y);
				// debug
				Messages::add('response', 'vertical moves = {');
				foreach($moves['vertical'] as $key => $value) {
					if($key >= count($moves['vertical']) - 1) Messages::add('response', '('.$value['x'].','.$value['y'].')');
					else Messages::add('response', '('.$value['x'].','.$value['y'].'), ');
				}
				Messages::add('response', '}' . PHP_EOL);

				$moves['diagonal'] = $this->possibleDiagonalMovement($x, $y);
				// debug

				// looking for blocked pawn
				if(empty($moves['horizontal']) && empty($moves['vertical'])	&& empty($moves['diagonal'])) {
					if($this->board[$x][$y] instanceOf Pawn) {
						array_push($this->staticPawns, array('x' => $x, 'y' => $y));
					}
				} else {
					// player can move pawn
				}
			} else {
				Messages::add('response', "That's NOT your turn, dumb !" . PHP_EOL);
			}
			$this->allowedMovement = $moves;
		}
		return false;
	}

	//this method was modified in order to give only one coordinate
	public function possibleHorizontalMovement($x, $y) {
		$allowed = array();
		if($x == 0) {
			// 0+ : checking on the right side
			$x++;
			while($x <= 4) {
				$target = $this->board[$y][$x];
				if($target != null) {
					break;
				} else {
					$x++;
				}
			}
			$allowed[] = array('x' => $x, 'y' => $y);
		} elseif($x == 4) {
			// 4- : checking on the left side
			$x--;
			while($x >= 0) {
				$target = $this->board[$y][$x];
				if($target 	!= null) {
					break;
				} else {
					$x--;
				}
			}
				$allowed[] = array('x' => $x, 'y' => $y);
		} elseif($x > 0 || $x < 4) {
			// 0 < x < 4
			$right = $x + 1;
			$left = $x - 1;

			// checking on the right side
			while($right <= 4) {
				$target = $this->board[$y][$right];
				if($target != null) {
					break;
				} else {
					$right++;
				}
			}
				$allowed[] = array('x' => $right, 'y' => $y);
			// checking on the left side
			while($left >= 0) {
				$target = $this->board[$y][$left];
				if($target != null) {
					break;
				} else {
					$left--;
				}
			}
			$allowed[] = array('x' => $left, 'y' => $y);
		}
		return $allowed;
	}

	//this method was modified in order to give only one coordinate
	public function possibleVerticalMovement($x, $y) {
		$allowed = array();
		if($y == 0) {
			// 0+ : checking on the top side
			$y++;
			while($y <= 4) {
				$target = $this->board[$y][$x];
				if($target != null) {
					
					break;
				} else {
					$y++;
				}
				
			}
			$allowed[] = array('x' => $x, 'y' => $y);
		} elseif($y == 4) {
			// 4- : checking on the bottom side
			$y--;
			while($y >= 0) {
				$target = $this->board[$y][$x];
				if($target != null) {
					break;
				} else {
					$y--;
				}
				
			}
			$allowed[] = array('x' => $x, 'y' => $y);
		} elseif($y > 0 || $y < 4) {
			// 0 < y < 4
			$top = $y + 1;
			$bottom = $y - 1;

			// checking on the top side
			while($top <= 4) {
				$target = $this->board[$top][$x];
				if($target != null) {
					break;
				} else {
					$top++;
				}
				
			}
			$allowed[] = array('x' => $x, 'y' => $top);
			// checking on the bottom side
			while($bottom >= 0) {
				$target = $this->board[$bottom][$x];
				if($target != null) {
					break;
				} else {
					$bottom--;
				}
				
			}
			$allowed[] = array('x' => $x, 'y' => $bottom);
		}
		return $allowed;
	}


	//TODO: Change this method to give only one coordinate.
	public function possibleDiagonalMovement($x, $y) {
		// checking bottom/left side
		// checking top/right side
		// checking bottom/right side
		// checking top/left side
		
		$possibleCoord = array();
		$cptX = $x - 1;
		$cptY = $y + 1;
		// checking bottom/left side
		if(($cptX >= 0) && ($cptY <= 4)){ // is to avoid an nuller pointer error on the board's array.
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX >= 0) && ($cptY <= 4)) && $cursorBox != null) {
				// array_push($possibleCoord, $cptX, $cptY);
				
				$cptX--;
				$cptY++;
				$cursorBox = $this->board[$cptX][$cptY];
			}
			array_push($possibleCoord, array($cptX, $cptY));
		}
		// checking top/right side
		$cptX = $x + 1;
		$cptY = $y - 1;
		if(($cptX <= 4) && ($cptY >= 0)){
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX <= 4) && ($cptY >= 0)) && $cursorBox != null) {
				// array_push($possibleCoord, $cptX, $cptY);
			
				$cptX++;
				$cptY--;
				$cursorBox = $this->board[$cptX][$cptY];
			}
				array_push($possibleCoord, array($cptX, $cptY));
		}
		// checking bottom/right side
		$cptX = $x + 1;
		$cptY = $y + 1;
		if(($cptX <= 4) && ($cptY <= 4)){
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX <= 4) && ($cptY <= 4)) && $cursorBox != null) {
				// array_push($possibleCoord, $cptX, $cptY);
				
				$cptX++;
				$cptY++;
				$cursorBox = $this->board[$cptX][$cptY];
			}
			array_push($possibleCoord, array($cptX, $cptY));
		}
		// checking top/left side
		$cptX = $x - 1;
		$cptY = $y - 1;
		if(($cptX >= 0) && ($cptY >= 0)){
			$cursorBox = $this->board[$cptX][$cptY];
			while((($cptX >= 0) && ($cptY >= 0)) && $cursorBox != null) {
				// array_push($possibleCoord, $cptX, $cptY);
			
				$cptX--;
				$cptY--;
				$cursorBox = $this->board[$cptX][$cptY];
			}
				array_push($possibleCoord, array($cptX, $cptY));
		}

		return $possibleCoord;
	}

	public function isAlone($x, $y){
		if($x == 4){
			if($y == 4){
				if((($this->board[$x][$y-1]== null) && ($this->board[$x-1][$y]== null)) && ($this->board[$x-1][$y-1]== null)){
					return true;
				}else{
					return false;
				}
			} elseif ($y == 0) {
				if((($this->board[$x][$y+1]== null) && ($this->board[$x-1][$y]== null)) && ($this->board[$x-1][$y+1]== null)){
					return true;
				}else{
					return false;
				}
			} else{
				// to finish
				if(((($this->board[$x][$y+1]== null) && 
					($this->board[$x-1][$y]== null)) && 
					($this->board[$x-1][$y+1]== null)) && 
					($this->board[$x-1][$y-1] == null)){
					return true;
				}else{
					return false;
				}
			}
		} elseif ($x == 0) {
			if($y == 4){
				if((($this->board[$x][$y-1]== null) && ($this->board[$x+1][$y]== null)) && ($this->board[$x+1][$y-1]== null)){
					return true;
				}else{
					return false;
				}
			} elseif ($y == 0) {
				if((($this->board[$x][$y+1]== null) && ($this->board[$x+1][$y]== null)) && ($this->board[$x+1][$y+1]== null)){
					return true;
				}else{
					return false;
				}
			} else{
				// to finish
				if(((($this->board[$x][$y+1]== null) && 
					($this->board[$x+1][$y]== null)) && 
					($this->board[$x+1][$y+1]== null)) && 
					($this->board[$x+1][$y-1] == null)){
					return true;
				}else{
					return false;
				}
		} else{
			if($y == 4){
				if((((($this->board[$x][$y-1]== null) && 
					($this->board[$x+1][$y]== null)) && 
					($this->board[$x+1][$y-1]== null))) &&
					($this->board[$x-1][$y] == null)){
					return true;
				}else{
					return false;
				}
			} elseif ($y == 0) {
				if((((($this->board[$x][$y+1]== null) && 
					($this->board[$x+1][$y]== null)) && 
					($this->board[$x+1][$y+1]== null)) &&
					($this->board[$x-1][$y] == null)) &&
					($this->board[$x-1][$y+1] == null)){
					
					return true;
				}else{
					return false;
				}
			} else{
				if(((((((($this->board[$x][$y+1]== null) && 
					($this->board[$x+1][$y]== null)) && 
					($this->board[$x+1][$y+1]== null)) && 
					($this->board[$x+1][$y-1] == null)) &&
					($this->board[$x-1][$y-1] == null)) &&
					($this->board[$x][$y-1] == null)) &&
					($this->board[$x-1][$y] == null)) &&
					($this->board[$x-1][$y-1] == null)) {
					return true;
				}else{
					return false;
				}
		}
	}

	public function endGame() {
		return ((count($this->$staticPawnYelow) >= 5) || (count($this->$staticPawnBlue) >= 5));
	}

	public function drawBoard() {
		$render = '<table><tbody>';

		$y = 0;
		foreach($this->board as $row) {
			$x = 0;
			$render .= '<tr>';
			foreach($row as $col) {
				$debug = '<span class="coordinates">'.$x.','.$y.'</span>';
				if($col != null) {
					$render .= '<td><div class="box"><a href="'.ROOT.'/index.php?p='.$col->getPlayer()->getNumPlayer().'&x='.$x.'&y='.$y.'" class="pawn '.$col->getColor().'"></a>'.$debug.'</div></td>';
				} else {
					$render .= '<td><div class="box">'.$debug.'</div></td>';
				}
				$x++;
			}
			$render .= '</tr>';
			$y++;
		}

		echo $render.'</tbody></table>';
	}

}
