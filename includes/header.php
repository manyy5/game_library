<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$wallet = null;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/db.php'; // lub inna poprawna ścieżka do db.php
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $wallet = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Game Library</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav>
    <img src="assets/images/logo2.png" alt="Logo GameLib" style="height: 100px; margin-right: 15px;">
    <a href="/game_library/index.php">Strona główna</a>
    <a href="/game_library/store.php">Sklep</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/game_library/library.php">Moja biblioteka</a>
        <a href="/game_library/wishlist.php">Lista życzeń</a>
        <a href="/game_library/profile.php">Profil (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <a href="users.php">Użytkownicy</a>
        <a href="/game_library/wallet.php">Portfel</a>
        <span style="margin-left:5px;  color: #00b894;
;">
            Saldo: <strong><?= number_format($wallet, 2) ?> zł</strong>
        </span>
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/game_library/logout.php">Wyloguj</a>
<?php endif; ?>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="/game_library/admin/dashboard.php">Panel admina</a>
        <?php endif; ?>
    <?php else: ?>
        <a href="/game_library/login.php">Logowanie</a>
        <a href="/game_library/register.php">Rejestracja</a>
    <?php endif; ?>
</nav>
<hr>
