<?php
require '../config/db.php';
require '../config/jwt.php';
require '../config/validator.php';
require '../config/user_repository.php';


$languages = [
  'Pascal','C','C++','JavaScript','PHP','Python','Java',
  'Haskell','Clojure','Prolog','Scala','Go'
];


$values = $_POST ?? [];
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $errors = validate_form($_POST);

    if (!$errors && email_exists($pdo, $_POST['email'])) {
        $errors['email'] = 'Пользователь с таким email уже существует';
    }
    if (!$errors && phone_exists($pdo, $_POST['phone'])) {
    $errors['phone'] = 'Пользователь с таким номером телефона уже существует';
    }


  if (!$errors) {
    
    $login = bin2hex(random_bytes(4));
    $password = bin2hex(random_bytes(4));
    $hash = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $pdo->prepare("
      INSERT INTO users
      (fio, phone, email, birthdate, gender, bio, contract_agreed, login, password_hash)
      VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)
    ");

    $stmt->execute([
      $_POST['fio'],
      $_POST['phone'],
      $_POST['email'],
      $_POST['birthdate'],
      $_POST['gender'],
      $_POST['bio'],
      $login,
      $hash
    ]);

    $userId = $pdo->lastInsertId();

    
    foreach ($_POST['languages'] as $lang) {
      $stmt = $pdo->prepare("SELECT id FROM languages WHERE name = ?");
      $stmt->execute([$lang]);
      $langId = $stmt->fetchColumn();

      if (!$langId) {
        $pdo->prepare("INSERT INTO languages (name) VALUES (?)")->execute([$lang]);
        $langId = $pdo->lastInsertId();
      }

      $pdo->prepare("
        INSERT INTO user_languages (user_id, language_id)
        VALUES (?, ?)
      ")->execute([$userId, $langId]);
    }

    
    setcookie(
      'token',
      jwt_create(['user_id' => $userId]),
      time() + 31536000,
      '/',
      '',
      false,
      true
    );

    header(
      'Location: edit.php?first=1&login=' .
      urlencode($login) .
      '&password=' .
      urlencode($password)
    );
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Первый вход</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<div class="card">

<h1>Первый вход</h1>

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
    <input name="fio"
      value="<?= htmlspecialchars($values['fio'] ?? '') ?>">
  </div>

  <div class="form-group">
    <label>Телефон</label>
    <input type="tel" name="phone"
      value="<?= htmlspecialchars($values['phone'] ?? '') ?>">
  </div>

  <div class="form-group">
    <label>Email</label>
    <input type="email" name="email"
      value="<?= htmlspecialchars($values['email'] ?? '') ?>">
  </div>

  <div class="form-group">
    <label>Дата рождения</label>
    <input type="date" name="birthdate"
      value="<?= htmlspecialchars($values['birthdate'] ?? '') ?>">
  </div>

  <fieldset>
    <label>Пол</label>
    <div class="radio-group">
      <label>
        <input type="radio" name="gender" value="male"
          <?= ($values['gender'] ?? '') === 'male' ? 'checked' : '' ?>>
        Мужской
      </label>
      <label>
        <input type="radio" name="gender" value="female"
          <?= ($values['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
        Женский
      </label>
    </div>
  </fieldset>

  <div class="form-group">
    <label>Любимые языки программирования</label>
    <select name="languages[]" multiple>
      <?php foreach ($languages as $lang): ?>
        <option value="<?= $lang ?>"
          <?= in_array($lang, $values['languages'] ?? []) ? 'selected' : '' ?>>
          <?= $lang ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label>Биография</label>
    <textarea name="bio"><?= htmlspecialchars($values['bio'] ?? '') ?></textarea>
  </div>

  <div class="form-group checkbox">
    <input type="checkbox" name="contract"
      <?= isset($values['contract']) ? 'checked' : '' ?>>
    <label>С контрактом ознакомлен(а)</label>
  </div>

  <button>Создать профиль</button>

</form>

<p class="footer-note">
  После сохранения будут выданы логин и пароль
</p>

</div>
</div>

</body>
</html>
