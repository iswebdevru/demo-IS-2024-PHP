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

?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
	<?php
	if (isset($_POST['auto']) && isset($_POST['problem']) && isset($_POST['date'])) {
		try {
			create_request($connection, [
				'auto' => $_POST['auto'],
				'booking_datetime' => $_POST['date'],
				'problem' => $_POST['problem'],
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
								<label for="auto">Авто</label>
								<input required type="text" name="auto" id="auto">
							</div>
							<div class="form__block">
								<label for="problem">Проблема</label>
								<input required type="text" name="problem" id="problem">
							</div>
							<div class="form__block">
								<label for="date">Дата</label>
								<input id="date" name="date" type="datetime-local"  required>
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