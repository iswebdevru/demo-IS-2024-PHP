<?php
session_start();
if (!empty($_SESSION['user_id'])) {
	header('Location:./profile.php');
	exit;
}
?>
<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
	<?php
	require_once(realpath(dirname(__FILE__)) . '/lib/db.php');
	require_once(realpath(dirname(__FILE__)) . '/lib/auth.php');

	$connection = create_db_connection();

	if (isset($_POST['email']) && isset($_POST['full_name']) && isset($_POST['phone']) && isset($_POST['driver_license']) && isset($_POST['pass'])) {
		try {
			create_user($connection, [
				'full_name' => $_POST['full_name'],
				'phone' => $_POST['phone'],
				'driver_license' => $_POST['driver_license'],
				'password' => $_POST['pass'],
				'email' => $_POST['email'],
			]);
		} catch (PDOException $e) {
			if ($e->getCode() == 23000) {
				header('Location:./register.php?error=true');
				exit();
			}
			exit("Error: " . $e->getMessage() . $e->getCode());
		}
		header('Location:./login.php');
		exit;
	} else {
		exit("Error: Некорректные данные");
	}
	?>
<?php else : ?>
	<!DOCTYPE html>
	<html lang="ru">

	<head>
		<meta charset="UTF-8">
		<title>Регистрация</title>
	</head>

	<body>
		<?php include("components/header.php"); ?>

		<div class="register">
			<div class="register__container">
				<form class="register__form form" method="POST" action="./register.php">

					<h2 class="form__header">Регистрация</h2>
					<div class="form__body">
						<div>
							<label class="form__input-label" for="full_name">ФИО</label>
							<input class="form__input input" type="text" id="full_name" name="full_name" required>
						</div>
						<div>
							<label class="form__input-label" for="email">Email</label>
							<input class="form__input input" type="text" id="email" name="email" required>
						</div>
						<div>
							<label class="form__input-label" for="phone">Номер телефона</label>
							<input class="form__input input" type="text" id="phone" name="phone" required>
						</div>
						<div>
							<label class="form__input-label" for="driver_license">Водительское удостоверение</label>
							<input class="form__input input" type="text" id="driver_license" name="driver_license" required>
						</div>

						<div>
							<label class="form__input-label" for="pass">Пароль</label>
							<input class="form__input input" type="password" id="pass" name="pass" required>

						</div>
					</div>

					<button type="submit" class="form__btn btn">Зарегистрироваться</button>
					<?php if (isset($_GET['error'])) : ?>
						<p class="from_wrong">Такой пользователь уже существует</p>
					<?php endif; ?>
				</form>
			</div>

		</div>
	</body>

	</html>
<?php endif; ?>