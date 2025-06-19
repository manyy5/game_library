<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Logowanie OK
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $errors[] = "Nieprawidłowy login lub hasło.";
    }
}
?>

<div class="container" style="max-width: 400px; margin: 60px auto;">
    <h2 class="centered-heading">Logowanie</h2>
    <?php
    if (isset($_GET['registered'])) 
        echo "<p class='success-msg'>Rejestracja zakończona sukcesem! Zaloguj się.</p>";
    if (!empty($errors)) {
        echo "<ul class='error-list'>";
        foreach ($errors as $e) echo "<li>$e</li>";
        echo "</ul>";
    }
    ?>
    <form method="post" class="login-form">
        <label for="username" class="login-label">
            Nazwa użytkownika lub e-mail:
            <input type="text" id="username" name="username" required class="login-input">
        </label>
        <label for="password" class="login-label">
            Hasło:
            <input type="password" id="password" name="password" required class="login-input">
        </label>
        <button type="submit" class="play-btn">Zaloguj się</button>
    </form>
    <a href="register.php">Nie masz konta? Zarejestruj się</a>
</div>

<?php
require_once 'includes/footer.php';
?>