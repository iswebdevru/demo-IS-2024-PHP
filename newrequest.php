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

$masters = get_masters($connection)
?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
	<?php
	if (isset($_POST['master']) && isset($_POST['datetime'])) {
		try {
			create_request($connection, [
				'id_master' => $_POST['master'],
				'booking_datetime' => $_POST['datetime'],
			]);
			header('Location:./newrequest.php');
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
		<title>Оставить заявку</title>
	</head>

	<body>
		<?php include("blocks/header.php"); ?>

		<main class="neworder">
			<div class="neworder__container">
				<div class="neworder__body">
					<form method="POST" action="newrequest.php" class="neworder__form form">
						<h2>Оставить заявку</h2>
						<div class="form__body">
							<div class="form__block">
								<label for="car_number">Мастер</label>
								<select required name="master" id="">
									<?php

									if (empty($masters)) {
										echo '';
									}
									foreach ($masters as $master) {
										echo "<option value='" . $master['id'] . "'>" . $master['name'] . "</option>";
									}
									?>

								</select>
							</div>
							<div class="form__block">
								<label for="description">Время</label>
								<input name="datetime" type="datetime-local" required>
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