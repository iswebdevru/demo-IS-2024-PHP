<?php

/**
 * Подключение к базе данных MySQL с использованием интерфейса PDO
 */
function create_db_connection()
{
  $DB_HOST = 'localhost';
  $DB_USER = 'root';
  $DB_DATABASE = 'demosite';
  $DB_PASSWORD = '123123';
  try {
    return new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_DATABASE, $DB_USER, $DB_PASSWORD);
  } catch (PDOException $e) {
    exit("Ошибка подключения к БД: " . $e->getMessage());
  }
}