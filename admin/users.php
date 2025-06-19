<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Zmiana roli użytkownika
if (isset($_GET['role']) && isset($_GET['id'])) {
    $role = $_GET['role'] === 'admin' ? 'admin' : 'user';
    $uid = intval($_GET['id']);
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$role, $uid]);
    header("Location: users.php");
    exit;
}

// Usuwanie użytkownika
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$del_id]);
    header("Location: users.php");
    exit;
}

// Pobierz użytkowników
$stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<style>
<?php include '../assets/css/style.css'; ?>
</style>

<h2>Zarządzaj użytkownikami</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nazwa użytkownika</th>
        <th>Email</th>
        <th>Rola</th>
        <th>Data rejestracji</th>
        <th>Akcje</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
        <td>
            <?php if ($user['role'] === 'user'): ?>
                <a href="users.php?role=admin&id=<?= $user['id'] ?>">Nadaj admina</a>
            <?php elseif ($user['role'] === 'admin'): ?>
                <a href="users.php?role=user&id=<?= $user['id'] ?>">Odbierz admina</a>
            <?php endif; ?>
            <?php if ($_SESSION['user_id'] !== $user['id']): ?>
                <a href="users.php?delete=<?= $user['id'] ?>" onclick="return confirm('Na pewno usunąć?')">Usuń</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<a href="dashboard.php">Powrót do panelu</a>
<?php require_once '../includes/footer.php'; ?>
