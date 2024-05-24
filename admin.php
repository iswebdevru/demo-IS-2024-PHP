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
?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
	<?php
	if (isset($_POST['status']) &&  $_POST['id']) {
		try {
			update_request($connection, $_POST['id'], $_POST['status']);
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

	$requests = get_all_requests($connection)
	?>
	<!DOCTYPE html>
	<html lang="ru">

	<head>
		<meta charset="UTF-8">
		<title>Админ панель</title>
	</head>

	<body>
		<?php include("components/header.php"); ?>
		<main class="orders">
			<div class="orders__container">
				<h2>Все заявления</h2>

				<table>
					<thead>
						<tr>
							<th>№</th>
							<th>ФИО</th>
							<th>Телефон</th>
							<th>Дата бронирования</th>
							<th>Авто</th>
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
							echo '<form action="admin.php" method="POST">';
							echo "<tr>";
							echo "	<td>" . $item[2] . "</td>";
						
							echo "<td>" . $item[3] . "</td>";
							echo "	<td>" . $item[4] . "</td>";
							echo "	<td>" . $item[1] . "</td>";
							echo "	<td>" . $item[6] . "</td>";
							echo "	<td>" . $item[5] . "</td>";

							echo '<input type="text" hidden name="id" value="' . $item[2] . '">';
							if ($item[7] === 1) {
								echo "<td><select  onchange='this.form.submit()' name='status'>
							<option value='1' selected>Новый</option>
							<option value='4'>Подтвержденный</option>
							<option value='3'>Отмененный</option>
							</select></td>";
							} elseif ($item[7] === 3) {
								echo "<td><select  onchange='this.form.submit()' name='status'>
							<option value='1'>Новый</option>
							<option value='4' >Подтвержденный</option>
							<option value='3' selected>Отмененный</option>
							</select></td>";
							} elseif ($item[7] === 4) {
								echo "<td><select  onchange='this.form.submit()' name='status'>
							<option value='1'>Новый</option>
							<option value='4' selected>Подтвержденный</option>
							<option value='3'>Отмененный</option>
							</select></td>";
							}

							echo "</tr>";
							echo "</form>";
						}
						?>
					</tbody>
				</table>


			</div>
		</main>
	</body>

	</html>
<?php endif; ?>