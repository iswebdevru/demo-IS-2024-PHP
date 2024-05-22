<?php

function get_products(PDO $connection)
{
	$query = $connection->prepare('SELECT * from product;');
	$query->execute();
	$products = $query->fetchAll();
	if (!$products) {
		return null;
	}
	return $products;
}

function create_request(PDO $connection, $request)
{
	$status_new = 1;
	$query = $connection->prepare("INSERT INTO `order`(count, id_product, address, id_user, id_status) VALUES (:count, :id_product, :address, :id_user, :id_status);");
	$query->bindParam("id_product", $request['id_product']);
	$query->bindParam("count", $request['count']);
	$query->bindParam("address", $request['address']);
	$query->bindParam("id_user", $_SESSION['user_id']);
	$query->bindParam("id_status", $status_new);
	return $query->execute();
}

/**
 * Получить заявления авторизованного пользователя из БД
 */
function get_my_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT product.name,  status.name, `order`.count, `order`.address, `order`.id FROM `order` LEFT join	status on status.id = `order`.id_status LEFT join product on product.id = `order`.id_product WHERE `order`.id_user = :user_id ;');
	$query->bindParam('user_id', $_SESSION['user_id']);
	$query->execute();
	$orders = $query->fetchAll();
	if (!$orders) {
		return null;
	}
	return $orders;
}


function get_all_requests(PDO $connection)
{
	$query = $connection->prepare('SELECT product.name, status.name, `order`.count ,`order`.address, `order`.id, user.full_name, user.email, status.id FROM `order` LEFT join	status on status.id = `order`.id_status LEFT join product on product.id = `order`.id_product  LEFT join user on user.id = `order`.id_user;');
	$query->execute();
	$orders = $query->fetchAll();
	if (!$orders) {
		return null;
	}
	return $orders;
}


function update_request(PDO $connection, $id, $status)
{
	$query = $connection->prepare('UPDATE `order` SET id_status = :id_status WHERE id=:id;');
	$query->bindParam('id', $id);
	$query->bindParam('id_status', $status);

	return $query->execute();
}
