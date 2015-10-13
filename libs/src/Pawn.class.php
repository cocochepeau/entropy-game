<?php

class Pawn {

	private $color;		//string
	private $player;	//player
	private $canMove;	//boolean
	private $x;		//baaaahh it's awfuuul! :(
	private $y;

	// Player 1: yellow
	// Player 2: blue

	public function __construct($player) {
		$this->player = $player;
		if($this->player->getNumPlayer() == 1) {
			$this->color = 'yellow';
		} else {
			$this->color = 'purple';
		}
	}
	
	public function getColor(){
		return $color;
	}
	
	public function setColor($colorSet){
		$this->color = $colorSet;
	}
	
	public function getPlayer() {
		return $this->player;
	}

	public function setCoord($newX, $newY){
		$this->x = $newX;
		$this->y = $newY;
	}

}
