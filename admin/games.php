<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Usuwanie gry
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
    $stmt->execute([$del_id]);
    header("Location: games.php");
    exit;
}

// Pobierz gry
$stmt = $pdo->query("SELECT * FROM games ORDER BY title ASC");
$games = $stmt->fetchAll();
?>

<style>
<?php include '../assets/css/style.css'; ?>
</style>

<h2>Zarządzaj grami</h2>
<a href="add_game.php">Dodaj nową grę</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tytuł</th>
        <th>Cena</th>
        <th>Akcje</th>
    </tr>
    <?php foreach ($games as $game): ?>
    <tr>
        <td><?= $game['id'] ?></td>
        <td><?= htmlspecialchars($game['title']) ?></td>
        <td><?= number_format($game['price'],2) ?> zł</td>
        <td>
            <a href="edit_game.php?id=<?= $game['id'] ?>">Edytuj</a>
            <a href="games.php?delete=<?= $game['id'] ?>" onclick="return confirm('Na pewno usunąć?')">Usuń</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require_once '../includes/footer.php'; ?>
