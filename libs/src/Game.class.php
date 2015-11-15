 <?php
class Game {

	private $playerOne;
	private $playerTwo;

	private $whichTurn = 1; // 1 = playerOne

	private $board;

	/*
	 * Store pawn source when attempting to move
	 */
	private $srcX;
	private $srcY;

	/*
	 * Store previous movement
	 */
	private $prevMovement = array();

	/*
	 * An array of available movements (horizontal,
	 * vertical, diagonal).
	 */
	private $availableMovements = array();

	/*
	 * Listing all pawns which can't move,
	 * then, we just have to check it for the
	 * endGame() method.
	 */
	private $blockedPawns = array();

	/*
	 * Listing all pawns which can't move,
	 * then, we just have to check it for the
	 * endGame() method.
	 */
	private $alonePawns = array();

	/*
	 * In order to reconnect with alone pawns
	 */
	private $rescuePawns = array();

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
	public function availableMovements($x, $y, $p) {
		// sanitizing
		$x = (int)$x;
		$y = (int)$y;
		$p = (int)$p;

		$this->srcX = $x;
		$this->srcY = $y;

		if(($x >= 0 || $x <= 4) && ($y >= 0 || $y <= 4)	&& ($p == 1 || $p == 2)) {
			$moves = array();
			if($this->whichTurn == $p) {
				// flush available movements
				$this->availableMovements = array();
				// storing available movements
				$this->horizontalMovements($x, $y);
				$this->verticalMovements($x, $y);
				$this->diagonalMovements($x, $y);
			} else {
				Messages::add("That's NOT your turn, dummy !");
			}
		}
		return false;
	}

	public function move($srcX, $srcY, $destX, $destY) {
		// do we have some allowed moves ?
		if(!empty($this->availableMovements)) {

			if(in_array(array('x' => $destX, 'y' => $destY), $this->availableMovements)) {
				// move selected pawn
				$this->board[$destY][$destX] = $this->board[$srcY][$srcX];
				$this->board[$srcY][$srcX] = null;

				// store movement to previous action
				$this->setPreviousMovement($srcX, $srcY, $destX, $destY);

				// clean up allowed moves
				$this->availableMovements = array();

				// looking for blocked & isolated pawns
				$this->scanPawns();

				// switch turn
				$this->switchTurn();

				return true;
			} else {
				Messages::add("Go get some fair play, cheater !");
			}
		}
		return false;
	}

	public function cancelMove() {
		if(!empty($this->prevMovement)) {
			// move pawn
			$this->board[$this->prevMovement['srcY']][$this->prevMovement['srcX']] = $this->board[$this->prevMovement['destY']][$this->prevMovement['destX']];
			$this->board[$this->prevMovement['destY']][$this->prevMovement['destX']] = null;

			// flush previous movement
			$this->prevMovement = array();

			// previous turn
			$this->switchTurn();

			return true;
		}
		return false;
	}

	public function setPreviousMovement($srcX, $srcY, $destX, $destY) {
		$this->prevMovement = array(
			'srcX' => $srcX,
			'srcY' => $srcY,
			'destX' => $destX,
			'destY' => $destY
		);
	}

	/*
	 * It returns true when we have a previous movement
	 * to handle.
	 */
	public function previousMovement() {
		if(!empty($this->prevMovement)) {
			return true;
		}
		return false;
	}

	public function switchTurn() {
		if($this->whichTurn == 1) {
			$this->whichTurn = 2;
			return 2;
		} elseif($this->whichTurn == 2) {
			$this->whichTurn = 1;
			return 1;
		}
	}

	/*
	 * return an array of available horizontal movement from $x and $y
	 */
	public function horizontalMovements($x, $y) {
		$tmpX = $x;

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
				$this->availableMovements[] = array('x' => $x-1, 'y' => $y);
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
				$this->availableMovements[] = array('x' => $x+1, 'y' => $y);
			}
		} elseif($x > 0 || $x < 4) {
			// 0 < x < 4
			$right = $x+1;
			$left = $x-1;

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
				$this->availableMovements[] = array('x' => $right-1, 'y' => $y);
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
				$this->availableMovements[] = array('x' => $left+1, 'y' => $y);
			}
		}
	}

	/*
	 * return an array of available vertical movement from $x and $y
	 */
	public function verticalMovements($x, $y) {
		$tmpY = $y;

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
			if($tmpY != $y -1) {
				$this->availableMovements[] = array('x' => $x, 'y' => $y-1);
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
			if($tmpY != $y+1) {
				$this->availableMovements[] = array('x' => $x, 'y' => $y+1);
			}
		} elseif($y > 0 || $y < 4) {
			// 0 < y < 4
			$bottom = $y+1;
			$top = $y-1;

			// checking on the bottom side
			while($bottom <= 4) {
				$target = $this->board[$bottom][$x];
				if($target != null) {
					break;
				} else {
					$bottom++;
				}
			}
			if($tmpY != $bottom-1) {
				$this->availableMovements[] = array('x' => $x, 'y' => $bottom-1);
			}

			// checking on the top side
			while($top >= 0) {
				$target = $this->board[$top][$x];
				if($target != null) {
					break;
				} else {
					$top--;
				}
			}
			if($tmpY != $top + 1) {
				$this->availableMovements[] = array('x' => $x, 'y' => $top+1);
			}
		}
	}

	/*
	 * return an array of available diagonal movement from $x and $y
	 */
	public function diagonalMovements($x, $y) {
		// checking bottom/left side
		$cptX = $x-1;
		$cptY = $y+1;
		if(($cptX >= 0) && ($cptY <= 4)){
			while((($cptX >= 0) && ($cptY <= 4)) && $this->board[$cptY][$cptX] == null) {
				$cptX--;
				$cptY++;
			}
			if(($cptX+1 != $x) && ($cptY-1 != $y)) {
				$this->availableMovements[] = array(
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
			if(($cptX-1 != $x) && ($cptY+1 != $y)) {
				$this->availableMovements[] = array(
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
			if(($cptX-1 != $x) && ($cptY-1 != $y)) {
				$this->availableMovements[] = array(
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
			if(($cptX+1 != $x) && ($cptY+1 != $y)) {
				$this->availableMovements[] = array(
					'x' => $cptX+1,
					'y' => $cptY+1
				);
			}
		}
	}

	/*
	 * parameters array which come from scanLoniless()
	 * @return array src of pawn and destination
	 */
	public function allowedMovedToAlone($alonePawns) {
		$pawnsCanMove = array();
		foreach($alonePawns as $pawn) {
			// horizontal movement
			$pawnsCanMove['horizontal'] = $this->allowedHorizontalMovementAlone($pawn['x'], $pawn['y']);
			//vertical movement
			$pawnsCanMove['vertical'] = $this->allowedVerticalMovementAlone($pawn['x'], $pawn['y']);
			//diagonal movement
			$pawnsCanMove['diagonal'] = $this->allowedDiagonalMovementAlone($pawn['x'], $pawn['y']);
		}
		return $pawnsCanMove;
	}

	public function allowedHorizontalMovementAlone($x, $y) {
		$tmpX = $x;
		$allowed = array();
		if($x == 0) {
			// 0+ : checking on the right side
			$x++;
			while($x <= 4) {
				$target = $this->board[$y][$x];
				if($target != null) {
					if($this->isBlocked($x, $y)){
						break;
					} else {
						$x++;
					}
				} else {
					$x++;
				}
			}
			if($x-1 != $tmpX){
				$allowed = array('xSrc' => $x-1, 'ySrc' => $y, 'xDest' => $tmpX+1, 'yDest' => $y);
			}
		} elseif($x == 4) {
			// 4- : checking on the left side
			$x--;
			while($x >= 0) {
				$target = $this->board[$y][$x];
				if($target != null) {
					if($this->isBlocked($x, $y)) {
							break;
						} else {
							$x--;
						}
				} else {
					$x--;
				}
			}
			if($x+1 != $tmpX) {
				$allowed = array('xSrc' => $x+1, 'ySrc' => $y, 'xDest' => $tmpX-1, 'yDest' => $y);
			}
		} elseif($x > 0 || $x < 4) {
			// 0 < x < 4
			$right = $x + 1;
			$left = $x - 1;

			// checking on the right side
			while($right <= 4) {
				$target = $this->board[$y][$right];
				if($target != null) {
					if($this->isBlocked($right, $y)) {
						break;
					} else {
						$right++;
					}
				} else {
					$right++;
				}
			}
			if($right-1 != $tmpX) {
				$allowed = array('xSrc' => $right-1, 'ySrc' => $y, 'xDest' => $tmpX+1, 'yDest' => $y);
			}

			// checking on the left side
			while($left >= 0) {
				$target = $this->board[$y][$left];
				if($target != null) {
					if($this->isBlocked($left, $y)) {
						break;
					} else {
						$left--;
					}
				} else {
					$left--;
				}
			}
			if($left+1 != $tmpX) {
				$allowed = array('xSrc' => $left+1, 'ySrc' => $y, 'xDest' => $tmpX-1,'yDest' => $y);
			}
		}
		return $allowed;
	}

	public function allowedVerticalMovementAlone($x, $y) {
		$allowed = array();

		$tmpY = $y;
		if($y == 0) {
			// 0+ : checking on the top side
			$y++;
			while($y <= 4) {
				$target = $this->board[$y][$x];
				if($target != null) {
					if($this->isBlocked($x, $y)) {
						break;
					} else {
						$y++;
					}
				} else {
					$y++;
				}
			}
			if($tmpY != $y-1) {
				$allowed = array('xSrc' => $x, 'ySrc' => $y-1, 'xDest' => $x, 'yDest' => $tmpY+1);
			}
		} elseif($y == 4) {
			// 4- : checking on the bottom side
			$y--;
			while($y >= 0) {
				$target = $this->board[$y][$x];
				if($target != null) {
					if($this->isBlocked($x, $y)) {
						break;
					} else {
						$y--;
					}
				} else {
					$y--;
				}
			}
			if($tmpY != $y+1){
				$allowed = array('xSrc' => $x, 'ySrc' => $y+1, 'xDest' => $x, 'yDest' => $tmpY-1);
			}
		} elseif($y > 0 || $y < 4) {
			// 0 < y < 4
			$top = $y + 1;
			$bottom = $y - 1;

			// checking on the top side
			while($top <= 4) {
				$target = $this->board[$top][$x];
				if($target != null) {
					if($this->isBlocked($x, $top)) {
						break;
					} else {
						$top++;
					}
				} else {
					$top++;
				}
			}
			if($tmpY != $top -1) {
				$allowed = array('xSrc' => $x, 'ySrc' => $top-1, 'xDest' => $x, 'yDest' => $tmpY+1);
			}

			// checking on the bottom side
			while($bottom >= 0) {
				$target = $this->board[$bottom][$x];
				if($target != null) {
					if($this->isBlocked($x, $bottom)) {
						break;
					} else {
						$bottom--;
					}
				} else {
					$bottom--;
				}
			}
			if($tmpY != $bottom+1) {
				$allowed = array('xSrc' => $x, 'ySrc' => $bottom+1, 'xDest' => $x, 'yDest' => $tmpY-1);
			}
		}
		return $allowed;
	}

	public function allowedDiagonalMovementAlone($x, $y) {
		$allowedMoves = array();

		// checking bottom/left side
		$cptX = $x-1;
		$cptY = $y+1;
		if(($cptX >= 0) && ($cptY <= 4)) {
			while(($cptX >= 0 && $cptY <= 4 && $this->board[$cptY][$cptX] == null) || !$this->isBlocked($cptX, $cptY)) {
				$cptX--;
				$cptY++;
			}
			if(($cptX+1 != $x) || ($cptY-1 != $y)) {
				$allowedMoves['bottomLeft'] = array(
					'xSrc' => $cptX+1,
					'ySrc' => $cptY-1,
					'xDest' => $x-1,
					'yDest' => $y+1
				);
			}
		}

		// checking top/right side
		$cptX = $x+1;
		$cptY = $y-1;
		if(($cptX <= 4) && ($cptY >= 0)) {
			while($cptX <= 4 && $cptY >= 0 &&  $this->board[$cptY][$cptX] == null || !$this->isBlocked($cptX, $cptY)) {
				$cptX++;
				$cptY--;
			}
			if(($cptX-1 != $x) || ($cptY+1 != $y)) {
				$allowedMoves['topRight'] = array(
					'xSrc' => $cptX-1,
					'ySrc' => $cptY+1,
					'xDest' => $x+1,
					'yDest' => $y-1
				);
			}
		}

		// checking bottom/right side
		$cptX = $x+1;
		$cptY = $y+1;
		if(($cptX <= 4) && ($cptY <= 4)) {
			while($cptX <= 4 && $cptY <= 4 && $this->board[$cptY][$cptX] == null || !$this->isBlocked($cptX, $cptY)) {
				$cptX++;
				$cptY++;
			}
			if(($cptX-1 != $x) || ($cptY-1 != $y)) {
				$allowedMoves['bottomRight'] = array(
					'xSrc' => $cptX-1,
					'ySrc' => $cptY-1,
					'xDest' => $x+1,
					'yDest' => $y+1
				);
			}
		}

		// checking top/left side
		$cptX = $x-1;
		$cptY = $y-1;
		if(($cptX >= 0) && ($cptY >= 0)) {
			while($cptX >= 0 && $cptY >= 0 && $this->board[$cptY][$cptX] == null  || !$this->isBlocked($cptX, $cptY)) {
				$cptX--;
				$cptY--;
			}
			if(($cptX+1 != $x) || ($cptY+1 != $y)) {
				$allowedMoves['topLeft'] = array(
					'xSrc' => $cptX+1,
					'ySrc' => $cptY+1,
					'xDest' => $x-1,
					'yDest' => $y-1
				);
			}
		}

		return $allowedMoves;
	}

	/*
	 * check if the pawn at (x,y) is blocked, return a boolean
	 */
	public function isBlocked($x, $y) {
		if($this->board[$y][$x] && $this->board[$y][$x] != null) {
			$player = $this->board[$y][$x]->getPlayer();
			return (
				// top
				!$this->isPlayerPawn($x, $y-1, $player)
				// right
				&& !$this->isPlayerPawn($x+1, $y, $player)
				// bottom
				&& !$this->isPlayerPawn($x, $y+1, $player)
				// left
				&& !$this->isPlayerPawn($x-1, $y, $player)
				// topLeft
				&& !$this->isPlayerPawn($x-1, $y-1, $player)
				// topRight
				&& !$this->isPlayerPawn($x+1, $y-1, $player)
				// bottomRight
				&& !$this->isPlayerPawn($x+1, $y+1, $player)
				// bottomLeft
				&& !$this->isPlayerPawn($x-1, $y+1, $player)
			);
		}
		return false;
	}

	/*
	 * check if the pawn at (x,y) is alone, return a boolean
	 */
	public function isAlone($x, $y) {
		if($this->board[$y][$x] && $this->board[$y][$x] != null) {
			return (
				// top
				$this->board[$y-1][$x] == null
				// right
				&& $this->board[$y][$x+1] == null
				// bottom
				&& $this->board[$y+1][$x] == null
				// left
				&& $this->board[$y][$x-1] == null
				// topLeft
				&& $this->board[$y-1][$x-1] == null
				// topRight
				&& $this->board[$y-1][$x+1] == null
				// bottomRight
				&& $this->board[$y+1][$x+1] == null
				// bottomLeft
				&& $this->board[$y+1][$x-1] == null
			);
		}
		return false;
	}

	/*
	 * verify if the pawn at (x,y) belongs to $player, return a boolean
	 */
	public function isPlayerPawn($x, $y, $player) {
		if($this->board[$y][$x] && $this->board[$y][$x] != null) {
			if($this->board[$y][$x]->getPlayer() == $player) {
				return true;
			}
		}
		return false;
	}

	/*
	 * scan all pawns on the board, it verify if pawns are alone or blocked
	 */
	public function scanPawns() {
		$this->blockedPawns = array(); // flush blocked pawns
		$this->alonePawns = array(); // flush isolated pawns
		$this->rescuePawns = array(); // flush rescue pawns

		for($y = 0; $y <=4; $y++) {
			for($x = 0; $x <= 4; $x++) {
				if($this->board[$y][$x] != null) {
					if($this->isAlone($x, $y)) {
						$this->alonePawns[] = array('x' => $x, 'y' => $y);
					} elseif($this->isBlocked($x, $y)) {
						$this->blockedPawns[] = array('x' => $x, 'y' => $y);
					}
				}
			}
		}

		// rescue
		$this->rescuePawns = $this->allowedMovedToAlone($this->alonePawns);
		var_dump($this->rescuePawns);
	}

	/*
	 * if the game is ended, return the winner, ifnot return null.
     */
	public function endGame() {
		$count = count($this->blockedPawns);
		if($count >= 5) {
			$yellow = 0;
			$blue = 0;
			foreach($this->blockedPawns as $pawnCoord) {
				$pawn = $this->board[$pawnCoord['y']][$pawnCoord['x']];
				if($pawn->getColor() == 'yellow') {
					$yellow++;
				} else {
					$blue++;
				}
			}

			// do we have a winner ?
			if($yellow >= 5) {
				return $this->playerOne;
			} elseif($blue >= 5) {
				return $this->playerTwo;
			}
		}
		return null;
	}

	public function drawBoard() {
		$render = '<table><tbody>';

		$y = 0;
		foreach($this->board as $row) {
			$x = 0;
			$render .= '<tr>';
			foreach($row as $col) {
				if($col != null) {
					// Yaay we found a pawn ! Let's display it.
					if(in_array(array('x' => $x, 'y' => $y), $this->alonePawns)) { // alone ?
						$render .= '<td><div class="box"><a href="#" class="pawn '.$col->getColor().' alone"></a>';
					} elseif(in_array(array('x' => $x, 'y' => $y), $this->blockedPawns)) { // blocked ?
						$render .= '<td><div class="box"><a href="#" class="pawn '.$col->getColor().' blocked"></a>';
					} else { // just a simple pawn that needs to be displayed
						$playable = ($col->getPlayer()->getNumber() == $this->whichTurn) ? 'playable' : '';
						$href = (!$this->endGame()) ? 'index.php?select&p='.$col->getPlayer()->getNumber().'&x='.$x.'&y='.$y : '#';
						$render .= '<td><div class="box"><a href="'.$href.'" class="pawn '.$col->getColor().' '.$playable.'"></a>';
					}
				} else {
					// movements
					if(in_array(array('x' => $x, 'y' => $y), $this->availableMovements)) {
						$href = (!$this->endGame()) ? 'index.php?move&srcX='.$this->srcX.'&srcY='.$this->srcY.'&destX='.$x.'&destY='.$y : '#';
						$render .= '<td><div class="box"><a href="'.$href.'" class="pawn movable"></a>';
					} else {
						// nothing to display...
						$render .= '<td><div class="box">';
					}
				}

				// debug
				$render .= '<span class="coordinates">'.$x.','.$y.'</span></div></td>';

				$x++;
			}
			$render .= '</tr>';
			$y++;
		}

		echo $render.'</tbody></table>';
	}

}
