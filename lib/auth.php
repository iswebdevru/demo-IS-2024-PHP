<?php

/**
 * Создает запись в бд о новом пользователе
 */
function create_user(PDO $connection, $user)
{
  $query = $connection->prepare("INSERT INTO user (email, id_role, login, password, full_name, phone) VALUES (:email, :id_role, :login, :password, :full_name, :phone)");
  
  $user_role_id = 1;
  $query->bindParam(":email", $user['email']);
  $query->bindParam(":id_role", $user_role_id);
  $query->bindParam(":login", $user['login']);
  $query->bindParam(":password", $user['password']);
  $query->bindParam(":full_name", $user['full_name']);
  $query->bindParam(":phone", $user['phone']);
  return $query->execute();
}

/**
 * Аутентификация пользователя в системе
 * В случае нахождения пользователя в бд и соответствии паролей возвращается `user_id`.
 * Во всех остальных случаях функция возвращает `null`
 */
function authenticate_user(PDO $connection, $credentials): int|null
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

/**
 * Выход из аккаунта
 */
function logout_user()
{
  session_start();
  unset($_SESSION['user_id']);
  unset($_SESSION['is_admin']);
}
