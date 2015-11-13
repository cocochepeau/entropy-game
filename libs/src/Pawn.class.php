<?php
class Pawn {

	private $color;
	private $player;

	public function __construct($player) {
		$this->player = $player;
		if($this->player->getNumber() == 1) {
			$this->setColor('yellow'); // player one
		} else {
			$this->setColor('blue'); // player two
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
