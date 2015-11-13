<?php
class Player {

	private $number;		
	private $name;

	public function __construct($number, $name) {
		$this->number = $number;
		$this->name = $name;
	}

	public function getNumber() {
		return $this->number;
	}

	public function getName() {
		return $this->name;
	}

}
