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

$requests = get_my_requests($connection)
?>


<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title>Мои заявки</title>
</head>

<body>
	<?php include("components/header.php"); ?>
	<main class="orders">
		<div class="orders__container">
			<h2>Мои заявки</h2>
			<table>
				<thead>
					<tr>
						<th>№</th>
						<th>Автомобиль</th>
						<th>Дата бронирования</th>
						<th>Проблема</th>
						<th>Статус</th>
						
					</tr>
				</thead>

				<tbody>
					<?php
	
					if (empty($requests)) {
						return;
					}
					foreach ($requests as $item) {
						echo "<tr>";
						echo "<td>" . $item['id'] . "</td>";
						echo "<td>" . $item[0] . "</td>";
						echo "<td>" . $item[2] . "</td>";
						echo "<td>" . $item[1] . "</td>";
						echo "<td>" . $item[4] . "</td>";
						

						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</main>
</body>

</html>