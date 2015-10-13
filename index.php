<?php
if(isset($_POST['']))
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
				
			</form>
			<?php
		}
		?>
		<script async src="assets/js/jquery-1.11.3.min.js"></script>
		<script async src="assets/js/custom.js"></script>
	</body>
</html>
