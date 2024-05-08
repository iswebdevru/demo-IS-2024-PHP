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

	if (isset($_POST['fio']) && isset($_POST['phone']) && $_POST['email'] && isset($_POST['username']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
		if ($_POST['pass'] != $_POST['pass2']) {
			exit("Error: пароли не совпадают");
		}
		try {
			create_user($connection, [
				'fio' => $_POST['fio'],
				'phone' => $_POST['phone'],
				'email' => $_POST['email'],
				'username' => $_POST['username'],
				'password' => $_POST['pass'],
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
		<link rel="stylesheet" href="./static/css/style.css">
	</head>

	<body>
		<?php include("blocks/header.php"); ?>

		<div class="register">
			<div class="register__container">
				<form class="register__form form" method="POST" action="./register.php">

					<h2 class="form__header">Регистрация</h2>
					<div class="form__body">
						<div class="form__block">
							<label class="form__input-label" for="fio">ФИО</label>
							<input class="form__input input" type="text" id="fio" name="fio" required>
						</div>
						<div class="form__block">
							<label class="form__input-label" for="phone">Номер телефона</label>
							<input class="form__input input" type="text" id="phone" name="phone" required>
						</div>
						<div class="form__block">
							<label class="form__input-label" for="email">Email</label>
							<input class="form__input input" type="text" id="email" name="email" required>

						</div>
						<div class="form__block">
							<label class="form__input-label" for="username">Логин</label>
							<input class="form__input input" type="text" id="username" name="username" required>

						</div>
						<div class="form__block">
							<label class="form__input-label" for="pass">Пароль</label>
							<input class="form__input input" minlength="6" type="password" id="pass" name="pass" required>

						</div>
						<div class="form__block">
							<label class="form__input-label" for="pass2">Повторите пароль</label>
							<input class="form__input input" minlength="6" type="password" id="pass2" name="pass2" required>
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