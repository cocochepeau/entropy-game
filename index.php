<?php include(__DIR__ . '/includes/core.php'); ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
	<head>
		<meta charset="<?= CHARSET; ?>">
		<title>Entropy Game</title>
		<link rel="stylesheet" type="text/css" href="assets/css/normalize.css">
		<link rel="stylesheet" type="text/css" href="assets/css/custom.css">
	</head>
	<body>
		<div class="wrapper">
			<h1 class="brand">Entropy Game</h1>
			<?php
			if($game) {
				$winner = $game->endGame();
				if($winner) {
					?>
					<div class="mgr">
						<h3>Nous avons un gagnant !</h3>
						<p>Il s'agit de <?= $winner->getName(); ?></p>
					</div>
					<?php
				}
				?>
				<div class="board">
					<?php $game->drawBoard(); ?>
				</div>
				<div class="mgr">
					<?php Messages::render(); ?>
					<div class="mgr-left">
						<h3 class="pn pn-one"><?= htmlentities($game->getPlayerOne()->getName(), HE_FLAGS, CHARSET); ?></h3>
						<h3 class="pn pn-two"><?= htmlentities($game->getPlayerTwo()->getName(), HE_FLAGS, CHARSET); ?></h3>
					</div>
					<div class="mgr-right">
						<form action="index.php" method="post">
							<button class="btn btn-block" type="submit" name="restart">Relancer partie</button>
							<?php
							if($game->previousMovement()) {
								?><button class="btn btn-block" type="submit" name="previous">Annuler mouvement</button><?php
							}
							?>
						</form>
					</div>
				</div>
				<?php
			} else {
				?>
				<div class="mgr">
					<form action="index.php" method="post" accept-charset="UTF-8">
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
	</body>
</html>
