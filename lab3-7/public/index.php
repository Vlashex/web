<?php
require '../config/db.php';
require '../config/jwt.php';

$token = $_COOKIE['token'] ?? '';
if ($token && jwt_verify($token)) {
  header('Location: edit.php');
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE login = ?");
  $stmt->execute([$_POST['login']]);
  $user = $stmt->fetch();

  if ($user && password_verify($_POST['password'], $user['password_hash'])) {
    setcookie(
      'token',
      jwt_create(['user_id' => $user['id']]),
      time() + 31536000,
      '/',
      '',
      false,
      true
    );
    header('Location: edit.php');
    exit;
  }

  $error = 'Неверный логин или пароль';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Вход</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<div class="card">

<h1>Вход</h1>

<?php if ($error): ?>
  <div class="errors"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">
  <div class="form-group">
    <label>Логин</label>
    <input name="login">
  </div>

  <div class="form-group">
    <label>Пароль</label>
    <input type="password" name="password">
  </div>

  <button>Войти</button>
</form>

<hr>

<a href="first.php">Первый вход</a>

</div>
</div>
</body>
</html>
