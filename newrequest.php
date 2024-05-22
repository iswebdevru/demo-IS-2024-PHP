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

$products = get_products($connection)
?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
	<?php
	if (isset($_POST['product']) && isset($_POST['count']) && isset($_POST['address'])) {
		try {
			create_request($connection, [
				'id_product' => $_POST['product'],
				'address' => $_POST['address'],
				'count' => $_POST['count'],
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
								<label for="product">Продукт</label>
								<select required name="product" id="product">
									<?php

									if (empty($products)) {
										echo '';
									}
									foreach ($products as $product) {
										echo "<option value='" . $product['id'] . "'>" . $product['name'] . "</option>";
									}
									?>

								</select>
							</div>
							<div class="form__block">
								<label for="count">Кол-во</label>
								<input id="count" name="count" type="number" min="0" required>
							</div>
							<div class="form__block">
								<label for="address">Адрес</label>
								<input name="address" type="text" required>
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