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

$orders = get_my_requests($connection)
?>


<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title>Заявки</title>
</head>

<body>
	<?php include("blocks/header.php"); ?>
	<main class="orders">
		<div class="orders__container">
			<h2>Заявки</h2>
			<table>
				<thead>
					<tr>
						<th>№</th>
						<th>Мастер</th>
						<th>Статус</th>
						<th>Дата бронирования</th>

					</tr>
				</thead>

				<tbody>
					<?php
					
					if (empty($orders)) {
						return;
					}
					foreach ($orders as $item) {
						echo "<tr>";
						echo "<td>" . $item['id'] . "</td>";
						echo "<td>" . $item[0] . "</td>";
						echo "<td>" . $item[1] . "</td>";
						echo "<td>" . $item['booking_datetime'] . "</td>";

						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</main>
</body>

</html>

