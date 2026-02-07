<?php
require '../config/db.php';
require '../config/validator.php';


if (
  !isset($_SERVER['PHP_AUTH_USER']) ||
  $_SERVER['PHP_AUTH_USER'] !== 'admin' ||
  $_SERVER['PHP_AUTH_PW'] !== 'adminpass'
) {
  header('WWW-Authenticate: Basic realm="Admin Area"');
  header('HTTP/1.0 401 Unauthorized');
  exit;
}

$userId = $_GET['id'] ?? null;
if (!$userId) {
  header('Location: admin.php');
  exit;
}


$languages = [
  'Pascal','C','C++','JavaScript','PHP','Python','Java',
  'Haskell','Clojure','Prolog','Scala','Go'
];


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
  header('Location: admin.php');
  exit;
}


$stmt = $pdo->prepare("
  SELECT l.name
  FROM user_languages ul
  JOIN languages l ON l.id = ul.language_id
  WHERE ul.user_id = ?
");
$stmt->execute([$userId]);
$userLangs = array_column($stmt->fetchAll(), 'name');

$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $errors = validate_form($_POST);
unset($errors['contract']);

if (
    !$errors &&
    email_exists($pdo, $_POST['email'], $userId)
) {
    $errors['email'] = 'Email уже используется другим пользователем';
}

if (
    !$errors &&
    phone_exists($pdo, $_POST['phone'], $userId)
) {
    $errors['phone'] = 'Телефон уже используется другим пользователем';
}


  if (!$errors) {

    $stmt = $pdo->prepare("
      UPDATE users
      SET fio=?, phone=?, email=?, birthdate=?, gender=?, bio=?
      WHERE id=?
    ");
    $stmt->execute([
      $_POST['fio'],
      $_POST['phone'],
      $_POST['email'],
      $_POST['birthdate'],
      $_POST['gender'],
      $_POST['bio'],
      $userId
    ]);

    $pdo->prepare("DELETE FROM user_languages WHERE user_id=?")
        ->execute([$userId]);

    foreach ($_POST['languages'] as $lang) {
      $stmt = $pdo->prepare("SELECT id FROM languages WHERE name=?");
      $stmt->execute([$lang]);
      $langId = $stmt->fetchColumn();

      if ($langId) {
        $pdo->prepare("
          INSERT INTO user_languages (user_id, language_id)
          VALUES (?,?)
        ")->execute([$userId, $langId]);
      }
    }

    header('Location: admin.php');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактирование пользователя</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<div class="card">

<h1>Редактирование анкеты</h1>

<?php if ($errors): ?>
  <div class="errors">
    <?php foreach ($errors as $e): ?>
      <p><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form method="POST">

  <div class="form-group">
    <label>ФИО</label>
    <input name="fio" value="<?= htmlspecialchars($user['fio']) ?>">
  </div>

  <div class="form-group">
    <label>Телефон</label>
    <input name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
  </div>

  <div class="form-group">
    <label>Email</label>
    <input name="email" value="<?= htmlspecialchars($user['email']) ?>">
  </div>

  <div class="form-group">
    <label>Дата рождения</label>
    <input type="date" name="birthdate"
      value="<?= htmlspecialchars($user['birthdate']) ?>">
  </div>

  <fieldset>
    <label>Пол</label>
    <div class="radio-group">
      <label>
        <input type="radio" name="gender" value="male"
          <?= $user['gender'] === 'male' ? 'checked' : '' ?>>
        Мужской
      </label>
      <label>
        <input type="radio" name="gender" value="female"
          <?= $user['gender'] === 'female' ? 'checked' : '' ?>>
        Женский
      </label>
    </div>
  </fieldset>

  <div class="form-group">
    <label>Любимые языки</label>
    <select name="languages[]" multiple>
      <?php foreach ($languages as $lang): ?>
        <option value="<?= $lang ?>"
          <?= in_array($lang, $userLangs) ? 'selected' : '' ?>>
          <?= $lang ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label>Биография</label>
    <textarea name="bio"><?= htmlspecialchars($user['bio']) ?></textarea>
  </div>

  <button>Сохранить</button>

</form>

<p class="footer-note">
  <a href="admin.php">← Назад в админку</a>
</p>

</div>
</div>

</body>
</html>
