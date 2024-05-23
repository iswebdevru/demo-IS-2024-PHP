<?php

function get_cars(PDO $connection)
{
	$query = $connection->prepare('SELECT * from car;');
	$query->execute();
	$cars = $query->fetchAll();
	if (!$cars) {
		return null;
	}
	return $cars;
}

function create_request(PDO $connection, $request)
{
	$status_new = 1;
	$query = $connection->prepare("INSERT INTO request(booking_date, id_car, id_user, id_status) VALUES (:booking_date, :id_car, :id_user, :id_status);");
	$query->bindParam("id_car", $request['id_car']);
	$query->bindParam("booking_date", $request['booking_date']);
	$query->bindParam("id_user", $_SESSION['user_id']);
	$query->bindParam("id_status", $status_new);
	return $query->execute();
}

function get_my_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT car.name,  status.name, request.booking_date, request.id FROM request LEFT join	status on status.id = request.id_status LEFT join car on car.id = request.id_car WHERE request.id_user = :user_id ;');
	$query->bindParam('user_id', $_SESSION['user_id']);
	$query->execute();
	$cars = $query->fetchAll();
	if (!$cars) {
		return null;
	}
	return $cars;
}


function get_all_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT car.name, status.name ,request.booking_date, request.id, user.full_name, user.phone ,  user.email, status.id FROM request LEFT join	status on status.id = request.id_status LEFT join car on car.id = request.id_car  LEFT join user on user.id = request.id_user;');
	$query->execute();
	$cars = $query->fetchAll();
	if (!$cars) {
		return null;
	}
	return $cars;
}


function update_request(PDO $connection, $id, $status)
{
	$query = $connection->prepare('UPDATE request SET id_status = :id_status WHERE id=:id;');
	$query->bindParam('id', $id);
	$query->bindParam('id_status', $status);

	return $query->execute();
}
