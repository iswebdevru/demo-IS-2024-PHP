<?php

function create_user(PDO $connection, $user)
{
  $query = $connection->prepare("INSERT INTO user (email, id_role, driver_license, password, full_name, phone) VALUES (:email, :id_role, :driver_license, :password, :full_name, :phone)");
  
  $user_role_id = 1;
  $query->bindParam(":email", $user['email']);
  $query->bindParam(":id_role", $user_role_id);
  $query->bindParam(":driver_license", $user['driver_license']);
  $query->bindParam(":password", $user['password']);
  $query->bindParam(":full_name", $user['full_name']);
  $query->bindParam(":phone", $user['phone']);
  return $query->execute();
}

function authenticate_user(PDO $connection, $credentials): int|null
{
  $query = $connection->prepare('SELECT * FROM user WHERE email=:email LIMIT 1');
  if (!$query) {
    return null;
  }
  $query->bindParam('email', $credentials['email']);
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
