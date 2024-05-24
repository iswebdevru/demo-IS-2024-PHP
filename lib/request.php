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
	$query = $connection->prepare("INSERT INTO request(booking_datetime, auto, id_user, id_status, problem) VALUES (:booking_datetime, :auto, :id_user, :id_status, :problem);");
	$query->bindParam("auto", $request['auto']);
	$query->bindParam("problem", $request['problem']);
	$query->bindParam("booking_datetime", $request['booking_datetime']);
	$query->bindParam("id_user", $_SESSION['user_id']);
	$query->bindParam("id_status", $status_new);
	return $query->execute();
}

function get_my_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT   request.auto, request.problem, request.booking_datetime, request.id, status.name FROM request LEFT join	status on status.id = request.id_status WHERE request.id_user = :user_id ;');
	$query->bindParam('user_id', $_SESSION['user_id']);
	$query->execute();
	$requests = $query->fetchAll();
	if (!$requests) {
		return null;
	}
	return $requests;
}


function get_all_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT  status.name ,request.booking_datetime, request.id, user.full_name, user.phone ,  request.problem, request.auto, status.id FROM request LEFT join	status on status.id = request.id_status  LEFT join user on user.id = request.id_user;');
	$query->execute();
	$requests = $query->fetchAll();
	if (!$requests) {
		return null;
	}
	return $requests;
}


function update_request(PDO $connection, $id, $status)
{
	$query = $connection->prepare('UPDATE request SET id_status = :id_status WHERE id=:id;');
	$query->bindParam('id', $id);
	$query->bindParam('id_status', $status);

	return $query->execute();
}
