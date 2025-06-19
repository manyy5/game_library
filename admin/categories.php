<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Dodawanie kategorii
$cat_msg = '';
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $desc]);
        $cat_msg = "Kategoria dodana!";
    } else {
        $cat_msg = "Podaj nazwę kategorii.";
    }
}

// Usuwanie kategorii
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$del_id]);
    header("Location: categories.php");
    exit;
}

// Pobierz kategorie
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<style>
<?php include '../assets/css/style.css'; ?>
</style>

<div class="container" style="max-width: 700px; margin: 60px auto;">
    <h2 class="centered-heading">Zarządzaj kategoriami</h2>
    <?php if ($cat_msg): ?>
        <p class="success-msg" style="text-align:center;"><?= htmlspecialchars($cat_msg) ?></p>
    <?php endif; ?>

    <form method="post" class="category-form">
        <label class="category-label">
            Nazwa:
            <input type="text" name="name" required class="category-input">
        </label>
        <label class="category-label">
            Opis:
            <input type="text" name="description" class="category-input">
        </label>
        <button type="submit" name="add_category" class="play-btn">Dodaj kategorię</button>
    </form>

    <div class="category-table-wrapper">
        <table class="category-table">
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Akcje</th>
            </tr>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td><?= htmlspecialchars($cat['description']) ?></td>
                <td>
                    <a href="categories.php?delete=<?= $cat['id'] ?>" onclick="return confirm('Na pewno usunąć?')">Usuń</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div style="text-align:center; margin-top: 18px;">
        <a href="dashboard.php">Powrót do panelu</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>