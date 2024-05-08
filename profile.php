<?php
session_start();
if (empty($_SESSION['user_id'])) {
  header('Location:./login.php');
  exit;
}
require_once(realpath(dirname(__FILE__)) . '/lib/db.php');
require_once(realpath(dirname(__FILE__)) . '/lib/user.php');
try {
  $connection = create_db_connection();
  $user = get_user($connection, $_SESSION['user_id']);
  if (!$user) {
    header('Location:./logout.php');
    exit;
  }
} catch (e) {
  header('Location:./logout.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title>Личный кабинет</title>
	<link rel="stylesheet" href="./static/css/style.css">
</head>

<body>
	<?php include("blocks/header.php"); ?>
	<main class="profile">
		<div class="profile__container">
			<h2>Информация о пользователе</h2>
			<div class="profile__body">

				<p>ФИО: <span class="">
						<?php echo $user['fio'] ?>
					</span></p>
				<p>Email: <span class="">
						<?php echo $user['email'] ?>
					</span></p>
				<p>Логин: <span class="">
						<?php echo $user['username'] ?>
					</span></p>
			</div>
		</div>
	</main>
</body>

</html>