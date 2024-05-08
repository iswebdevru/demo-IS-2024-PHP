<?php
session_start();
if (empty($_SESSION['user_id'])) {
	header('Location:./login.php');
	exit;
}
require_once(realpath(dirname(__FILE__)) . '/lib/db.php');
require_once(realpath(dirname(__FILE__)) . '/lib/user.php');
require_once(realpath(dirname(__FILE__)) . '/lib/order.php');
$connection = create_db_connection();
try {
	$user = get_user($connection, $_SESSION['user_id']);
	if (!$user) {
		header('Location:./logout.php');
		exit;
	}
} catch (PDOException $e) {
	header('Location:./logout.php');
	exit;
}
?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
	<?php
	if (isset($_POST['car_number']) && isset($_POST['description'])) {
		try {
			create_order($connection, [
				'car_number' => $_POST['car_number'],
				'description' => $_POST['description'],
			]);
			header('Location:./neworder.php');
		} catch (PDOException $e) {
			exit("Error: " . $e->getMessage());
		}
	}

	?>

<?php else : ?>
	<!DOCTYPE html>
	<html lang="ru">

	<head>
		<meta charset="UTF-8">
		<title>Написать заявление</title>
		<link rel="stylesheet" href="./static/css/style.css">
	</head>

	<body>
		<?php include("blocks/header.php"); ?>

		<main class="neworder">
			<div class="neworder__container">
				<div class="neworder__body">
					<form method="POST" action="neworder.php" class="neworder__form form">
						<h2>Написать заявление</h2>
						<div class="form__body">
							<div class="form__block">
								<label for="car_number">Номер машины</label>
								<input class="input" name="car_number" type="text">
							</div>
							<div class="form__block">
								<label for="description">Описание</label>
								<textarea class="input" name="description" id="description" cols="30" rows="10"></textarea>
							</div>
						</div>

						<button type="submit" class="form__btn btn">Отправить</button>
					</form>
				</div>
			</div>
		</main>
	</body>

	</html>
<?php endif; ?>