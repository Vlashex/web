<?php
require '../config/db.php';


if (
    !isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_USER'] !== 'admin' ||
    $_SERVER['PHP_AUTH_PW'] !== 'adminpass'
) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}


if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: admin.php');
    exit;
}


$users = $pdo->query("
    SELECT id, fio, email, phone, birthdate, gender
    FROM users
    ORDER BY id ASC
")->fetchAll();



$langStmt = $pdo->prepare("
    SELECT l.name
    FROM user_languages ul
    JOIN languages l ON l.id = ul.language_id
    WHERE ul.user_id = ?
");


$stats = $pdo->query("
    SELECT l.name, COUNT(*) AS cnt
    FROM user_languages ul
    JOIN languages l ON l.id = ul.language_id
    GROUP BY l.name
    ORDER BY cnt DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Администрирование</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }
    th, td {
      padding: 10px;
      border-bottom: 1px solid #e5e7eb;
      text-align: left;
    }
    th {
      background: #f3f4f6;
    }
    .actions a {
      margin-right: 10px;
      color: #2563eb;
    }
    .danger {
      color: #dc2626;
    }
  </style>
</head>
<body>

<div class="container-wide">
<div class="card-wide">

<h1>Админ-панель</h1>

<h2>Пользователи</h2>

<table>
  <tr>
    <th>ID</th>
    <th>ФИО</th>
    <th>Email</th>
    <th>Телефон</th>
    <th>Дата рождения</th>
    <th>Пол</th>
    <th>Языки</th>
    <th>Действия</th>
  </tr>

  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= htmlspecialchars($u['fio']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['phone']) ?></td>
      <td><?= htmlspecialchars($u['birthdate']) ?></td>
      <td><?= htmlspecialchars($u['gender']) ?></td>
      <td>
        <?php
          $langStmt->execute([$u['id']]);
          $langs = array_column($langStmt->fetchAll(), 'name');
          echo htmlspecialchars(implode(', ', $langs));
        ?>
      </td>
      <td class="actions">
        <a href="admin_edit.php?id=<?= $u['id'] ?>">Редактировать</a>
        <a class="danger"
           href="admin.php?delete=<?= $u['id'] ?>"
           onclick="return confirm('Удалить пользователя?')">
           Удалить
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<hr>

<h2>Статистика языков программирования</h2>

<table>
  <tr>
    <th>Язык</th>
    <th>Количество пользователей</th>
  </tr>
  <?php foreach ($stats as $s): ?>
    <tr>
      <td><?= htmlspecialchars($s['name']) ?></td>
      <td><?= $s['cnt'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<p class="footer-note">
  Администраторский доступ защищён HTTP-авторизацией
</p>

</div>
</div>

</body>
</html>
