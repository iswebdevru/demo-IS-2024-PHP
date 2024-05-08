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
?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
<?php
	if (isset($_POST['status']) &&  $_POST['id']) {
		try {
			update_order($connection, $_POST['id'], $_POST['status']);
			header('Location:./admin.php');
			exit;
		} catch (PDOException $e) {
			exit("Error: " . $e->getMessage());
		}
	}
	?>
<?php else : ?>

<?php
	if (empty($_SESSION['is_admin'])) {
		header('Location:./login.php');
		exit;
	}
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

	$orders = get_all_orders($connection)
	?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title>Админ панель</title>
	<link rel="stylesheet" href="./static/css/style.css">
</head>

<body>
	<?php include("blocks/header.php"); ?>
	<main class="orders">
		<div class="orders__container">
			<h2>Все заявления</h2>
			<form action="admin.php" method="POST">
				<table>
					<thead>
						<tr>
							<th>№</th>
							<th>Номер машины</th>
							<th>Описание</th>
							<th>Статус</th>
							<th>ФИО</th>
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
									echo "<td><select onchange='this.form.submit()' name='status'>
							<option value='0'>Новый</option>
							<option value='1'>Подтвержденный</option>
							<option value='2'>Отклоненный</option>
							</select></td>";
									echo '<input type="text" hidden name="id" value="' . $item['id'] . '">';
								} elseif ($item['status'] === '1') {
									echo "<td>Подтверждено</td>";
								} elseif ($item['status'] === '2') {
									echo "<td>Отклонено</td>";
								}
								echo "	<td>" . $item['fio'] . "</td>";
								echo "</tr>";
							}
							?>
					</tbody>
				</table>
			</form>

		</div>
	</main>
</body>

</html>
<?php endif; ?>