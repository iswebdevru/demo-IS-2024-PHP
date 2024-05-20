<?php

/**
 * Получить пользователя из базы данных
 */
function get_user(PDO $connection, int $user_id)
{
  $query = $connection->prepare('SELECT * FROM user WHERE id=:user_id LIMIT 1');
  $query->bindParam('user_id', $user_id);
  $query->execute();
  return $query->fetch();
}
