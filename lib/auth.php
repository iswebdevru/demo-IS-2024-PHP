<?php

/**
 * Создает запись в бд о новом пользователе
 */
function create_user(PDO $connection, $user)
{
  $password = password_hash($user['password'], PASSWORD_BCRYPT);
  $query = $connection->prepare("INSERT INTO users(fio, email, phone, username, password) VALUES (:fio, :email, :phone, :username, :password)");
  $query->bindParam("fio", $user['fio']);
  $query->bindParam("email", $user['email']);
  $query->bindParam("phone", $user['phone']);
  $query->bindParam("username", $user['username']);
  $query->bindParam("password", $password);
  return $query->execute();
}

/**
 * Аутентификация пользователя в системе
 * В случае нахождения пользователя в бд и соответствии паролей возвращается `user_id`.
 * Во всех остальных случаях функция возвращает `null`
 */
function authenticate_user(PDO $connection, $credentials): int|null
{
  $query = $connection->prepare('SELECT * FROM users WHERE username=:username LIMIT 1');
  if (!$query) {
    return null;
  }
  $query->bindParam('username', $credentials['username']);
  $query->execute();
  $user = $query->fetch();
  if (!$user) {
    return null;
  }
  if (password_verify($credentials['password'], $user['password'])) {
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
