<?php

function get_masters(PDO $connection)
{
	$query = $connection->prepare('SELECT * from master;');
	$query->execute();
	$masters = $query->fetchAll();
	if (!$masters) {
		return null;
	}
	return $masters;
}

/**
 * Создание заявления в БД
 */
function create_request(PDO $connection, $request)
{
	$status_new = 1;
	$query = $connection->prepare("INSERT INTO request(id_master, booking_datetime, id_user, id_status) VALUES (:id_master, :booking_datetime, :id_user, :id_status);");
	$query->bindParam("id_master", $request['id_master']);
	$query->bindParam("booking_datetime", $request['booking_datetime']);
	$query->bindParam("id_user", $_SESSION['user_id']);
	$query->bindParam("id_status", $status_new);
	return $query->execute();
}

/**
 * Получить заявления авторизованного пользователя из БД
 */
function get_my_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT master.name, status.name, request.booking_datetime, request.id FROM request LEFT join	status on status.id = request.id_status LEFT join master on master.id = request.id_master WHERE request.id_user = :user_id ;');
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
function get_all_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT master.name, status.name, request.booking_datetime, request.id, user.full_name, user.phone, status.id FROM request LEFT join	status on status.id = request.id_status LEFT join master on master.id = request.id_master  LEFT join user on user.id = request.id_user;');
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
function update_request(PDO $connection, $id, $status)
{
	$query = $connection->prepare('UPDATE request SET id_status = :id_status WHERE id=:id;');
	$query->bindParam('id', $id);
	$query->bindParam('id_status', $status);

	return $query->execute();
}
