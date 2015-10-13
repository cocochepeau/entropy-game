<?php
// Rapporte toutes les erreurs à part les E_NOTICE
// C'est la configuration par défaut de php.ini
error_reporting(E_ALL & ~E_NOTICE);

if(isset($_POST['start']))
{
	$playerOne = new Player();
	$playerTwo = new Player();
	$game = new Game();
}


?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<title>Entropy Game</title>
		<link rel="stylesheet" type="text/css" href="assets/css/custom.css">
	</head>
	<body>
		<?php
		if($game)
		{
			?>
			<div class="board">
				<?php $game->drawBoard(); ?>
			</div>
			<div class="mgr">
				<form action="index.php" method="post">
					<button type="submit" name="restart">Relancer</button>
				</form>
			</div>
			<?php
		}
		else
		{
			?>
			<h1>Entropy Game</h1>
			<form action="index.php" method="post" accept-charset="UTF-8">
				<label for="playerOne">Joueur 1</label>
				<input type="text" name="playerOne" id="playerOne" required>
				<label for="playerOne">Joueur 1</label>
				<input type="text" name="playerOne" id="playerOne" required>
				<button type="submit" name="start">C'est parti !</button>
			</form>
			<?php
		}
		?>
		<script async src="assets/js/jquery-1.11.3.min.js"></script>
		<script async src="assets/js/custom.js"></script>
	</body>
</html>
