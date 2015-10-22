<?php include(__DIR__ . '/includes/core.php'); ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
	<head>
		<meta charset="<?= CHARSET; ?>">
		<title>Entropy Game</title>
		<link rel="stylesheet" type="text/css" href="<?= ROOT; ?>/assets/css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?= ROOT; ?>/assets/css/custom.css">
	</head>
	<body>
		<div class="wrapper">
			<h1 class="brand">Entropy Game</h1>
			<?php
			if($game)
			{
				?>
				<div class="board">
					<?php $game->drawBoard(); ?>
				</div>
				<div class="mgr">
					<h3 class="pn pn-one"><?= htmlentities($game->getPlayerOne()->getNamePlayer(), HE_FLAGS, CHARSET); ?></h3>
					<h3 class="pn pn-two"><?= htmlentities($game->getPlayerTwo()->getNamePlayer(), HE_FLAGS, CHARSET); ?></h3>
					<form action="<?= ROOT; ?>/index.php" method="post">
						<button class="btn btn-block" type="submit" name="restart">Relancer</button>
					</form>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="mgr">
					<form action="<?= ROOT; ?>/index.php" method="post" accept-charset="UTF-8">
						<div class="form-control">
							<label for="playerOne">Joueur 1</label>
							<input type="text" name="playerOne" id="playerOne" required>
						</div>
						<div class="form-control">
						<label for="playerTwo">Joueur 2</label>
						<input type="text" name="playerTwo" id="playerTwo" required>
						</div>
						<div>
							<button class="btn btn-block" type="submit" name="start">C'est parti !</button>
						</div>
					</form>
				</div>
				<?php
			}
			?>
		</div>
		<!--<script async src="assets/js/jquery-1.11.3.min.js"></script>-->
		<script async src="assets/js/custom.js"></script>
	</body>
</html>
