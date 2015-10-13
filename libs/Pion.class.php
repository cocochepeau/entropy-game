<?php

class Pion{
	private $color;
	private $player;
	
// Player 1: yellow
// Player 2: blue

	public function __construct($player){
		$this->player = player;
		if($player->getNumPlayer() == 1){
			$this->color = "yellow";
		}else{
			$this->color = "purple";
		}
	
	}
	
	public function getColor(){
		return $color;
	}
	
	public function setColor($colorSet){
		$this->color = $colorSet;
	}
	
	public function getPlayer(){
		return $this->player;
	}
}


