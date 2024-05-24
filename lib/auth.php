<?php

function create_user(PDO $connection, $user)
{
  $query = $connection->prepare("INSERT INTO user (login, id_role, password, full_name, phone) VALUES (:login, :id_role, :password, :full_name, :phone)");
  
  $user_role_id = 1;
  $query->bindParam(":login", $user['login']);
  $query->bindParam(":id_role", $user_role_id);
  $query->bindParam(":password", $user['password']);
  $query->bindParam(":full_name", $user['full_name']);
  $query->bindParam(":phone", $user['phone']);
  return $query->execute();
}

function authenticate_user(PDO $connection, $credentials)
{
  $query = $connection->prepare('SELECT * FROM user WHERE login=:login LIMIT 1');
  if (!$query) {
    return null;
  }
  $query->bindParam('login', $credentials['login']);
  $query->execute();
  $user = $query->fetch();
  if (!$user) {
    return null;
  }
  if ($credentials['password'] === $user['password']) {
    return $user['id'];
  }
  return null;
}

function logout_user()
{
  session_start();
  unset($_SESSION['user_id']);
  unset($_SESSION['is_admin']);
}
