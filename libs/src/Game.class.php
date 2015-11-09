 <?php
class Game {

	private $playerOne;
	private $playerTwo;
	private $board;
	private $whichTurn = 1; // 1 = playerOne
	private $allowedMoves;
	private $srcX;
	private $srcY;

	/*
	 * array to list all pawns which can't move,
	 * it's quick to check in this array if the game is ended.
	 */
	private $staticPawns = array();

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
	public function possibleMovement($x, $y, $p) {
		// sanitizing
		$x = (int)$x;
		$y = (int)$y;
		$p = (int)$p;

		$this->srcX = $x;
		$this->srcY = $y;

		if(($x >= 0 || $x <= 4) && ($y >= 0 || $y <= 4)	&& ($p == 1 || $p == 2)) {
			$moves = array();
			if($this->whichTurn == $p) {
				// get horizontal moves
				$moves['horizontal'] = $this->possibleHorizontalMovement($x, $y);
				
				Messages::add('response', 'horizontal move = ('.$moves['horizontal']['x'].','.$moves['horizontal']['y'].')' . PHP_EOL);

				// get vertical moves
				$moves['vertical'] = $this->possibleVerticalMovement($x, $y);
				Messages::add('response', 'vertical move = ('.$moves['vertical']['x'].','.$moves['vertical']['y'].')' . PHP_EOL);

				// get diagonal moves
				$moves['diagonal'] = $this->possibleDiagonalMovement($x, $y);
				// Messages::add('response', 'diagonal move = ('.$moves['diagonal']['x'].','.$moves['diagonal']['y'].')' . PHP_EOL);

				// looking for blocked pawn
				if(empty($moves['horizontal']) && empty($moves['vertical'])	&& empty($moves['diagonal'])) {
					if($this->board[$x][$y] != null) {
						array_push($this->staticPawns, array('x' => $x, 'y' => $y));
					}
				} else {
					// player can move pawn
				}
			} else {
				Messages::add('response', "That's NOT your turn, dummy !" . PHP_EOL);
			}
			$this->allowedMoves = $moves;
		}
		return false;
	}

	public function move($srcX, $srcY, $destX, $destY) {
		// todo: check if movements are allowed for security
		if(!empty($this->allowedMoves)) {
			$allowed = true;
		} else {
			$allowed = false;
		}

		// and then move if allowed
		if($allowed) {
			$this->board[$destY][$destX] = $this->board[$srcY][$srcX];
			$this->board[$srcY][$srcX] = null;

			// clean up allowed moves
			$this->allowedMoves = array();

			// switch turn
			if($this->whichTurn == 1) {
				$this->whichTurn = 2;
			} elseif($this->whichTurn == 2) {
				$this->whichTurn = 1;
			}
		}
	}

	// this method was modified in order to give only one coordinate
	public function possibleHorizontalMovement($x, $y) {
		$tmpX = $x;
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
			if($x-1 != $tmpX){
				$allowed = array('x' => $x-1, 'y' => $y);
			}
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
			if($x+1 != $tmpX){
				$allowed = array('x' => $x+1, 'y' => $y);
			}
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
			if($right-1 != $tmpX){
				$allowed = array('x' => $right-1, 'y' => $y);
			}

			// checking on the left side
			while($left >= 0) {
				$target = $this->board[$y][$left];
				if($target != null) {
					break;
				} else {
					$left--;
				}
			}
			if($left+1 != $tmpX){
				$allowed = array('x' => $left+1, 'y' => $y);
			}
		}
		return $allowed;
	}

	//this method was modified in order to give only one coordinate
	public function possibleVerticalMovement($x, $y) {
		$tmpY = $y;
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
			if($tmpY != $y -1){
				$allowed = array('x' => $x, 'y' => $y-1);
			}
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
			if($tmpY != $y+1){
				$allowed = array('x' => $x, 'y' => $y+1);
			}
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
			if($tmpY != $top -1){
				$allowed = array('x' => $x, 'y' => $top-1);
			}

			// checking on the bottom side
			while($bottom >= 0) {
				$target = $this->board[$bottom][$x];
				if($target != null) {
					break;
				} else {
					$bottom--;
				}
			}
			if($tmpY != $bottom+1){
				$allowed = array('x' => $x, 'y' => $bottom+1);
			}
		}
		return $allowed;
	}

	public function possibleDiagonalMovement($x, $y) {
		$allowedMoves = array();
		$cptX = $x-1;
		$cptY = $y+1;

		// checking bottom/left side
		if(($cptX >= 0) && ($cptY <= 4)){
			while((($cptX >= 0) && ($cptY <= 4)) && $this->board[$cptY][$cptX] == null) {
				$cptX--;
				$cptY++;
			}
			if(($cptX+1 != $x) || ($cptY-1 != $y)) {
				$allowedMoves['bottomLeft'] = array(
					'x' => $cptX+1,
					'y' => $cptY-1
				);
			}
		}

		// checking top/right side
		$cptX = $x+1;
		$cptY = $y-1;
		if(($cptX <= 4) && ($cptY >= 0)) {
			while((($cptX <= 4) && ($cptY >= 0)) &&  $this->board[$cptY][$cptX] == null) {
				$cptX++;
				$cptY--;
			}
			if(($cptX-1 != $x) || ($cptY+1 != $y)) {
				$allowedMoves['topRight'] = array(
					'x' => $cptX-1,
					'y' => $cptY+1
				);
			}
		}

		// checking bottom/right side
		$cptX = $x+1;
		$cptY = $y+1;
		if(($cptX <= 4) && ($cptY <= 4)) {
			while((($cptX <= 4) && ($cptY <= 4)) && $this->board[$cptY][$cptX] == null) {
				$cptX++;
				$cptY++;
			}
			if(($cptX-1 != $x) || ($cptY-1 != $y)) {
				$allowedMoves['bottomRight'] = array(
					'x' => $cptX-1,
					'y' => $cptY-1
				);
			}
		}

		// checking top/left side
		$cptX = $x-1;
		$cptY = $y-1;
		if(($cptX >= 0) && ($cptY >= 0)) {
			while((($cptX >= 0) && ($cptY >= 0)) && $this->board[$cptY][$cptX] == null) {
				$cptX--;
				$cptY--;
			}
			if(($cptX+1 != $x) || ($cptY+1 != $y)) {
				$allowedMoves['topLeft'] = array(
					'x' => $cptX+1,
					'y' => $cptY+1
				);
			}
		}

		return $allowedMoves;
	}

	public function isAlone($x, $y) {
		if($x == 4) {
			if($y == 4) {
				if((($this->board[$y-1][$x]== null) && ($this->board[$y][$x-1]== null)) && ($this->board[$y-1][$x-1]== null)) {
					return true;
				}
			} elseif ($y == 0) {
				if((($this->board[$y+1][$x]== null) && ($this->board[$y][$x-1]== null)) && ($this->board[$y+1][$x-1]== null)) {
					return true;
				}
			} else {
				// to finish
				if(((($this->board[$y+1][$x]== null) && 
					($this->board[$y][$x-1]== null)) && 
					($this->board[$y+1][$x-1]== null)) && 
					($this->board[$y-1][$x-1] == null)) {
					return true;
				}
			}
		} elseif ($x == 0) {
			if($y == 4) {
				if((($this->board[$y-1][$x]== null) && ($this->board[$y][$x+1]== null)) && ($this->board[$y-1][$x+1]== null)) {
					return true;
				}
			} elseif ($y == 0) {
				if((($this->board[$y+1][$x]== null) && ($this->board[$y][$x+1]== null)) && ($this->board[$x+1][$y+1]== null)) {
					return true;
				}
			} else{
				// to finish
				if(((($this->board[$y+1][$x]== null) && 
					($this->board[$y][$x+1]== null)) && 
					($this->board[$y+1][$x+1]== null)) && 
					($this->board[$y-1][$x+1] == null)) {
					return true;
				}
			}
		} elseif($y == 4) {
				if((((($this->board[$y-1][$x]== null) && 
					($this->board[$y][$x+1]== null)) && 
					($this->board[$y-1][$x+1]== null))) &&
					($this->board[$y][$x-1] == null)) {
					return true;
				}
			} elseif ($y == 0) {
				if((((($this->board[$y+1][$x]== null) && 
					($this->board[$y][$x+1]== null)) && 
					($this->board[$y+1][$x+1]== null)) &&
					($this->board[$y][$x-1] == null)) &&
					($this->board[$y+1][$x-1] == null)) {
					return true;
				}
			} else{
				if(((((((($this->board[$y+1][$x]== null) && 
					($this->board[$y][$x+1]== null)) && 
					($this->board[$y+1][$x+1]== null)) && 
					($this->board[$y-1][$x+1] == null)) &&
					($this->board[$y-1][$x-1] == null)) &&
					($this->board[$y-1][$x] == null)) &&
					($this->board[$y][$x-1] == null)) &&
					($this->board[$y-1][$x-1] == null)) {
					return true;
				}else{return false;}
			}
		}
		return false;
	}

	public function allowedMovedToAlone($alonePawns) {
		// parameters array which come from scanLoniless()
		// return array src of pawn and destination
		$aloneX = 0;
		$aloneY = 0;
		$count = count($alonePawns);

		for($i = 0; i <= sizeof($alonePawns) -1 ; $i++) {
			$aloneX = $alonePawns[$i]["x"];
			$aloneY = $alonePawns[$i]["y"];
		}
	}
	
	public function scanLoniless() {
		//scan all pawn if it alone...
		//return an array of alone pawns
		$alonePawns = array();
		for($i = 0; $i<=4; $i++){
			for($k = 0; $k <= 4; $k++){
				if($this->isAlone($k, $i)){
					$alonePawns[] = array('x' => $k, 'y' => $i);
				}
			}
		}

		return $alonePawns;
	}

	public function isBlocked($x, $y) {
		$pawnColor = $this->board[$y][$x]->getColor();
		if($x == 4) {
			if($y == 4) {
				if((($this->board[$y-1][$x]->getColor() != $pawnColor) || ($this->board[$y][$x-1]->getColor() != $pawnColor)) || ($this->board[$y-1][$x-1]->getColor() != $pawnColor)) {
					return true;
				}
			} elseif ($y == 0) {
				if((($this->board[$y+1][$x]->getColor() != $pawnColor) || ($this->board[$y][$x-1]->getColor() != $pawnColor)) || ($this->board[$y+1][$x-1]->getColor() != $pawnColor)) {
					return true;
				}
			} else {
				// to finish
				if(((($this->board[$y+1][$x]->getColor() != $pawnColor) ||
					($this->board[$y][$x-1]->getColor() != $pawnColor)) ||
					($this->board[$y+1][$x-1]->getColor() != $pawnColor)) ||
					($this->board[$y-1][$x-1]->getColor() != $pawnColor)) {
					return true;
				}
			}
		} elseif ($x == 0) {
			if($y == 4) {
				if((($this->board[$y-1][$x]->getColor() != $pawnColor) || ($this->board[$y][$x+1]->getColor() != $pawnColor)) || ($this->board[$y-1][$x+1]->getColor() != $pawnColor)) {
					return true;
				}
			} elseif ($y == 0) {
				if((($this->board[$y+1][$x]->getColor() != $pawnColor) || ($this->board[$y][$x+1]->getColor() != $pawnColor)) || ($this->board[$x+1][$y+1]->getColor() != $pawnColor)) {
					return true;
				}
			} else{
				// to finish
				if(((($this->board[$y+1][$x]->getColor() != $pawnColor) || 
					($this->board[$y][$x+1]->getColor() != $pawnColor)) || 
					($this->board[$y+1][$x+1]->getColor() != $pawnColor)) || 
					($this->board[$y-1][$x+1]->getColor() != $pawnColor)) {
					return true;
				}
			}
		} elseif($y == 4) {
			if((((($this->board[$y-1][$x]->getColor() != $pawnColor) || 
				($this->board[$y][$x+1]->getColor() != $pawnColor)) || 
				($this->board[$y-1][$x+1]->getColor() != $pawnColor))) ||
				($this->board[$y][$x-1]->getColor() != $pawnColor)) {
				return true;
			}
		} elseif ($y == 0) {
			if((((($this->board[$y+1][$x]->getColor() != $pawnColor) || 
				($this->board[$y][$x+1]->getColor() != $pawnColor)) || 
				($this->board[$y+1][$x+1]->getColor() != $pawnColor)) ||
				($this->board[$y][$x-1]->getColor() != $pawnColor)) ||
				($this->board[$y+1][$x-1]->getColor() != $pawnColor)) {
				return true;
			}
		} else {
			if(((((((($this->board[$y+1][$x]->getColor() != $pawnColor) ||
				($this->board[$y][$x+1]->getColor() != $pawnColor)) || 
				($this->board[$y+1][$x+1]->getColor() != $pawnColor)) || 
				($this->board[$y-1][$x+1]->getColor() != $pawnColor)) ||
				($this->board[$y-1][$x-1]->getColor() != $pawnColor)) ||
				($this->board[$y-1][$x]->getColor() != $pawnColor)) ||
				($this->board[$y][$x-1]->getColor() != $pawnColor)) ||
				($this->board[$y-1][$x-1]->getColor() != $pawnColor)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	public function scanBlocked() {
		for($y = 0; $y <=4; $y++) {
			for($x = 0; $x <= 4; $x++) {
				if($this->board[$y][$x] != null) {
					if($this->isBlocked($x, $y)) {
						$this->staticPawns[] = array('x' => $x, 'y' => $y);
					}
				}
			}
		}
	}

	public function endGame() {
		$count = count($this->staticPawns);
		if($count >= 5) {
			$yellow = 0;
			$blue = 0;
			foreach($this->staticPawns as $pawn) {
				if($pawn->getColor() == 'yellow') {
					$yellow++;
				} else {
					$blue++;
				}
			}

			// do we have a winner ?
			if($yellow >= 5) {
				$this->playerOne();
			} elseif($blue >= 5) {
				$this->playerTwo();
			}
		}
		return false;
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
					if($col->getPlayer()->getNumPlayer() == $this->whichTurn) {
						$render .= '<td><div class="box"><a href="'.ROOT.'/index.php?select&p='.$col->getPlayer()->getNumPlayer().'&x='.$x.'&y='.$y.'" class="pawn '.$col->getColor().' playable"></a>'.$debug.'</div></td>';
					} else {
						$render .= '<td><div class="box"><a href="'.ROOT.'/index.php?select&p='.$col->getPlayer()->getNumPlayer().'&x='.$x.'&y='.$y.'" class="pawn '.$col->getColor().'"></a>'.$debug.'</div></td>';
					}
				} else {
					// moves
					if(
						$this->allowedMoves['horizontal']['x'] == $x && $this->allowedMoves['horizontal']['y'] == $y
						|| $this->allowedMoves['vertical']['x'] == $x && $this->allowedMoves['vertical']['y'] == $y
						|| $this->allowedMoves['diagonal']['topLeft']['x'] == $x && $this->allowedMoves['diagonal']['topLeft']['y'] == $y
						|| $this->allowedMoves['diagonal']['topRight']['x'] == $x && $this->allowedMoves['diagonal']['topRight']['y'] == $y
						|| $this->allowedMoves['diagonal']['bottomLeft']['x'] == $x && $this->allowedMoves['diagonal']['bottomLeft']['y'] == $y
						|| $this->allowedMoves['diagonal']['bottomRight']['x'] == $x && $this->allowedMoves['diagonal']['bottomRight']['y'] == $y
					) {
						$render .= '<td><div class="box"><a href="'.ROOT.'/index.php?move&srcX='.$this->srcX.'&srcY='.$this->srcY.'&destX='.$x.'&destY='.$y.'" class="pawn movable"></a>'.$debug.'</div></td>';
					} else {
						$render .= '<td><div class="box">'.$debug.'</div></td>';
					}
				}
				$x++;
			}
			$render .= '</tr>';
			$y++;
		}

		echo $render.'</tbody></table>';
	}

}
