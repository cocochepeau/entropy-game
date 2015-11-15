<?php

	public function isAlone($x, $y) {
		if($x == 4) {
			if($y == 4) {
				if((($this->board[$y-1][$x] == null) && ($this->board[$y][$x-1] == null)) && ($this->board[$y-1][$x-1] == null)) {
					return true;
				}
			} elseif ($y == 0) {
				if((($this->board[$y+1][$x] == null) && ($this->board[$y][$x-1] == null)) && ($this->board[$y+1][$x-1] == null)) {
					return true;
				}
			} else {
				// to finish
				if(((($this->board[$y+1][$x] == null) && 
					($this->board[$y][$x-1] == null)) && 
					($this->board[$y+1][$x-1] == null)) && 
					($this->board[$y-1][$x-1] == null)) {
					return true;
				}
			}
		} elseif ($x == 0) {
			if($y == 4) {
				if((($this->board[$y-1][$x] == null) && ($this->board[$y][$x+1] == null)) && ($this->board[$y-1][$x+1] == null)) {
					return true;
				}
			} elseif ($y == 0) {
				if((($this->board[$y+1][$x] == null) && ($this->board[$y][$x+1] == null)) && ($this->board[$x+1][$y+1] == null)) {
					return true;
				}
			} else {
				// to finish
				if(((($this->board[$y+1][$x] == null) && 
					($this->board[$y][$x+1] == null)) && 
					($this->board[$y+1][$x+1] == null)) && 
					($this->board[$y-1][$x+1] == null)) {
					return true;
				}
			}
		} elseif($y == 4) {
			if((((($this->board[$y-1][$x] == null) && 
				($this->board[$y][$x+1] == null)) && 
				($this->board[$y-1][$x+1] == null))) &&
				($this->board[$y][$x-1] == null)) {
				return true;
			}
		} elseif ($y == 0) {
			if((((($this->board[$y+1][$x] == null) && 
				($this->board[$y][$x+1] == null)) && 
				($this->board[$y+1][$x+1] == null)) &&
				($this->board[$y][$x-1] == null)) &&
				($this->board[$y+1][$x-1] == null)) {
				return true;
			}
		} else {
			if(((((((($this->board[$y+1][$x] == null) && 
				($this->board[$y][$x+1] == null)) && 
				($this->board[$y+1][$x+1] == null)) && 
				($this->board[$y-1][$x+1] == null)) &&
				($this->board[$y-1][$x-1] == null)) &&
				($this->board[$y-1][$x] == null)) &&
				($this->board[$y][$x-1] == null)) &&
				($this->board[$y-1][$x-1] == null)) {
				return true;
			}
		}
		return false;
	}
	
	/*
	 * parameters array which come from scanLoniless()
	 * @return array src of pawn and destination
	 */
	public function allowedMovedToAlone($alonePawns) {
		$aloneX = 0;
		$aloneY = 0;
		$alonePawnsSize = count($alonePawns);
		$pawnsCanMove = array();
		for($i = 0; $i <= $alonePawnsSize-1 ; $i++) {
			$aloneX = $alonePawns[$i]['x'];
			$aloneY = $alonePawns[$i]['y'];
			// horizontal movement
			$pawnsCanMove['horizontal'] = $this->allowedHorizontalMovementAlone($aloneX, $aloneY);
			//vertical movement
			$pawnsCanMove['vertical'] = $this->allowedVerticalMovementAlone($aloneX, $aloneY);
			//diagonal movement
			$pawnsCanMove['diagonal'] = $this->allowedDiagonalMovementAlone($saloneX, $aloneY);
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
					if($this->isBlocked($y, $x)){
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
					if($this->isBlocked($y, $x)){
							break;
						} else {
							$x--;
						}
				} else {
					$x--;
				}
			}
			if($x+1 != $tmpX) {
				$allowed = array('xSrc' => $x+1, 'ySrc' => $y, 'xDest' => $tmpX - 1, 'yDest' => $y);
			}
		} elseif($x > 0 || $x < 4) {
			// 0 < x < 4
			$right = $x + 1;
			$left = $x - 1;

			// checking on the right side
			while($right <= 4) {
				$target = $this->board[$y][$right];
				if($target != null) {
					if($this->isBlocked($y, $right)) {
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
					if($this->isBlocked($y, $left)) {
						break;
					} else {
						$left--;
					}
				} else {
					$left--;
				}
			}
			if($left+1 != $tmpX){
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
					if($this->isBlocked($y, $x)) {
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
					if($this->isBlocked($y, $x)) {
						break;
					} else {
						$y--;
					}
				} else {
					$y--;
				}
			}
			if($tmpY != $y+1){
				$allowed = array('xSrc' => $x, 'ySrc' => $y+1, 'xDest' => $x, 'yDest' => $tmpY - 1);
			}
		} elseif($y > 0 || $y < 4) {
			// 0 < y < 4
			$top = $y + 1;
			$bottom = $y - 1;

			// checking on the top side
			while($top <= 4) {
				$target = $this->board[$top][$x];
				if($target != null) {
					if($this->isBlocked($top, $x)) {
						break;
					} else {
						$top++;
					}
				} else {
					$top++;
				}
			}
			if($tmpY != $top -1) {
				$allowed = array('xSrc' => $x, 'ySrc' => $top-1, 'xDest' => $x, 'yDest' => $tmpY + 1);
			}

			// checking on the bottom side
			while($bottom >= 0) {
				$target = $this->board[$bottom][$x];
				if($target != null) {
					if($this->isBlocked($bottom, $x)) {
						break;
					} else {
						$bottom--;
					}
				} else {
					$bottom--;
				}
			}
			if($tmpY != $bottom+1) {
				$allowed = array('xSrc' => $x, 'ySrc' => $bottom+1, 'xDest' => $x, 'yDest' => $tmpY - 1);
			}
		}
		return $allowed;
	}

	public function allowedDiagonalMovementAlone($x, $y) {
		$allowedMoves = array();

		// checking bottom/left side
		$cptX = $x-1;
		$cptY = $y+1;
		if(($cptX >= 0) && ($cptY <= 4)){ // is to avoid an nuller pointer error on the board's array.
			while(((($cptX >= 0) && ($cptY <= 4)) && $this->board[$cptY][$cptX] == null) || (!$this->isBlocked($cptX, $cptY))) {
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
			while(((($cptX <= 4) && ($cptY >= 0)) &&  $this->board[$cptY][$cptX] == null) || (!$this->isBlocked($cptX, $cptY))) {
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
			while(((($cptX <= 4) && ($cptY <= 4)) && $this->board[$cptY][$cptX] == null) || (!$this->isBlocked($cptX, $cptY))) {
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
			while(((($cptX >= 0) && ($cptY >= 0)) && $this->board[$cptY][$cptX] == null)  || (!$this->isBlocked($cptX, $cptY))) {
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
