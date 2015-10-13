<?php
class Player{
	
	private $numPlayer;		
		
	public function __construct($num){
		$this->numPlayer = $num;
	}
	
	
	public function getNumPlayer(){
		return $this->numPlayer;
	}

?>
