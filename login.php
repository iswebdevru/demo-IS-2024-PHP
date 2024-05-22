<?php
session_start();
if (!empty($_SESSION['user_id'])) {
  header('Location:./requests.php');
  exit;
}
?>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
  <?php
  require_once(realpath(dirname(__FILE__)) . '/lib/db.php');
  require_once(realpath(dirname(__FILE__)) . '/lib/auth.php');
  require_once(realpath(dirname(__FILE__)) . '/lib/user.php');

  $connection = create_db_connection();

  if (isset($_POST['login']) && isset($_POST['password'])) {
    try {
      $user_id = authenticate_user($connection, [
        'login' => $_POST['login'],
        'password' => $_POST['password'],
      ]);

      if (empty($user_id)) {
        header('Location:./login.php?error=true');
      } else {
        echo $user_id;
        $_SESSION['user_id'] = $user_id;
        $user = get_user($connection, $user_id);
        if ($user['id_role'] === 2) {
          $_SESSION['is_admin'] = true;
        }
        header('Location:./requests.php');
        exit;
      }
    } catch (PDOException $e) {
      exit("Error: " . $e->getMessage());
    }
  } else {
    exit("Error: Некорректные данные");
  }

  ?>
<?php else : ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <title>Вход</title>
  </head>

  <body>
    <?php include("blocks/header.php"); ?>
    <div class="login">
      <div class="login__container">
        <form class="login__form form" action="./login.php" method="post">
          <h2 class="form__header">Авторизация</h2>
          <div class="form__body">
            <div class="form__block">
              <label for="username">Логин</label>
              <input placeholder="sklad" type="text" id="username" name="login" class="input" required>
            </div>
            <div class="form__block">
              <label for="password">Пароль</label>
              <input placeholder="123qwe" type="password" id="password"  name="password" class="input" required>
            </div>
          </div>
          <button type="submit" class="form__btn btn">Войти</button>
          <?php if (isset($_GET['error'])) : ?>
            <p class="from_wrong">Неверный логин или пароль</p>
          <?php endif; ?>
        </form>
      </div>
    </div>

  </body>

  </html>
<?php endif; ?>