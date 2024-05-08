<?php

/**
 * Создание заявления в БД
 */
function create_order(PDO $connection, $order)
{
	$query = $connection->prepare("INSERT INTO orders(car_number, description, user_id) VALUES (:car_number, :description, :user_id);");
	$query->bindParam("car_number", $order['car_number']);
	$query->bindParam("description", $order['description']);
	$query->bindParam("user_id", $_SESSION['user_id']);
	return $query->execute();
}

/**
 * Получить заявления авторизованного пользователя из БД
 */
function get_my_orders(PDO $connection)
{
	$query = $connection->prepare('SELECT * FROM orders WHERE user_id=:user_id;');
	$query->bindParam('user_id', $_SESSION['user_id']);
	$query->execute();
	$orders = $query->fetchAll();
	if (!$orders) {
		return null;
	}
	return $orders;
}

/**
 * Получить все заявления
 */
function get_all_orders(PDO $connection)
{
	$query = $connection->prepare('SELECT orders.id, orders.status, orders.description, orders.car_number, users.fio FROM orders LEFT join	users on orders.user_id = users.id;');
	$query->execute();
	$orders = $query->fetchAll();
	if (!$orders) {
		return null;
	}
	return $orders;
}

/**
 * Обновить статус заявления
 */
function update_order(PDO $connection, $id, $status)
{
	$query = $connection->prepare('UPDATE orders SET status = :status WHERE id=:id;');
	$query->bindParam('id', $id);
	$query->bindParam('status', $status);

	return $query->execute();
}
