<?php
session_start();
if (empty($_SESSION['user_id'])) {
	header('Location:./login.php');
	exit;
}
require_once(realpath(dirname(__FILE__)) . '/lib/db.php');
require_once(realpath(dirname(__FILE__)) . '/lib/user.php');
require_once(realpath(dirname(__FILE__)) . '/lib/request.php');
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

$cars = get_cars($connection)
?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
	<?php
	if (isset($_POST['car']) && isset($_POST['date'])) {
		try {
			create_request($connection, [
				'id_car' => $_POST['car'],
				'booking_date' => $_POST['date'],
			]);
			header('Location:./requests.php');
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
		<title>Сформировать заявку</title>
	</head>

	<body>
		<?php include("components/header.php"); ?>

		<main class="neworder">
			<div class="neworder__container">
				<div class="neworder__body">
					<form method="POST" action="newrequest.php" class="neworder__form form">
						<h2>Сформировать заявку</h2>
						<div class="form__body">
							<div class="form__block">
								<label for="product">Авто</label>
								<select required name="car" id="car">
									<?php

									if (empty($cars)) {
										echo '';
									}
									foreach ($cars as $car) {
										echo "<option value='" . $car['id'] . "'>" . $car['name'] . "</option>";
									}
									?>

								</select>
							</div>
							<div class="form__block">
								<label for="date">Дата</label>
								<input id="date" name="date" type="date" min="0" required>
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