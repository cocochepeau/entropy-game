<?php
class Player {

	private $numPlayer;		
	private $namePlayer;

	public function __construct($num, $name) {
		$this->numPlayer = $num;
		$this->namePlayer = $name;
	}

	public function getNumPlayer() {
		return $this->numPlayer;
	}

	public function getNamePlayer() {
		return $this->namePlayer;
	}

}
