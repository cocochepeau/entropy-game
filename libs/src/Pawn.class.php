<?php
class Pawn {

	private $color;
	private $player;

	public function __construct($player) {
		$this->player = $player;
		if($this->player->getNumPlayer() == 1) {
			$this->setColor('yellow');
		} else {
			$this->setColor('blue');
		}
	}

	public function getColor() {
		return $this->color;
	}
	
	public function setColor($colorSet){
		$this->color = $colorSet;
	}
	
	public function getPlayer() {
		return $this->player;
	}

}
