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

$orders = get_my_orders($connection)
?>


<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title>Заявления</title>
	<link rel="stylesheet" href="./static/css/style.css">
</head>

<body>
	<?php include("blocks/header.php"); ?>
	<main class="orders">
		<div class="orders__container">
			<h2>Заявления</h2>
			<table>
				<thead>
					<tr>
						<th>№</th>
						<th>Номер машины</th>
						<th>Описание</th>
						<th>Статус</th>

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
						echo "<td>" . $item['car_number'] . "</td>";
						echo "	<td>" . $item['description'] . "</td>";
						if ($item['status'] === '0') {
							echo "<td>Новый</td>";
						} elseif ($item['status'] === '1') {
							echo "<td>Подтверждено</td>";
						} elseif ($item['status'] === '2') {
							echo "<td>Отклонено</td>";
						}
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</main>
</body>

</html>