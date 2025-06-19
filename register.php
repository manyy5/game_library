<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    // Walidacja
    if (empty($username) || empty($email) || empty($password) || empty($password2)) {
        $errors[] = "Wszystkie pola są wymagane.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nieprawidłowy adres e-mail.";
    }
    if ($password !== $password2) {
        $errors[] = "Hasła się nie zgadzają.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Hasło musi mieć co najmniej 6 znaków.";
    }

    // Sprawdź czy użytkownik istnieje
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $errors[] = "Taki użytkownik lub e-mail już istnieje.";
    }

    // Dodaj użytkownika
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hash]);
        header("Location: login.php?registered=1");
        exit;
    }
}
?>

<div class="container" style="max-width: 400px; margin: 60px auto;">
    <h2 class="centered-heading">Rejestracja</h2>
    <?php
    if (!empty($errors)) {
        echo "<ul class='error-list'>";
        foreach ($errors as $e) echo "<li>$e</li>";
        echo "</ul>";
    }
    ?>
    <form method="post" class="register-form">
        <label for="username" class="register-label">
            Nazwa użytkownika:
            <input type="text" id="username" name="username" required class="register-input">
        </label>
        <label for="email" class="register-label">
            E-mail:
            <input type="email" id="email" name="email" required class="register-input">
        </label>
        <label for="password" class="register-label">
            Hasło:
            <input type="password" id="password" name="password" required class="register-input">
        </label>
        <label for="password2" class="register-label">
            Powtórz hasło:
            <input type="password" id="password2" name="password2" required class="register-input">
        </label>
        <button type="submit" class="play-btn">Zarejestruj się</button>
    </form>
    <a href="login.php">Masz konto? Zaloguj się</a>
</div>
