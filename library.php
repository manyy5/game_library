<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Pobierz kategorie do filtrowania
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();

// Pobierz parametry wyszukiwania i filtrowania
$q = trim($_GET['q'] ?? '');
$category_id = intval($_GET['category_id'] ?? 0);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Pobierz gry zakupione przez użytkownika z filtrowaniem po tytule i kategorii
$sql = "SELECT DISTINCT g.* FROM games g
        INNER JOIN purchases p ON g.id = p.game_id
        WHERE p.user_id = ?";
$params = [$user_id];

if ($category_id) {
    $sql .= " AND EXISTS (
        SELECT 1 FROM game_categories gc WHERE gc.game_id = g.id AND gc.category_id = ?
    )";
    $params[] = $category_id;
}
if ($q) {
    $sql .= " AND g.title LIKE ?";
    $params[] = '%' . $q . '%';
}
$sql .= " ORDER BY g.title ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$games = $stmt->fetchAll();
?>

<h2 class="centered-heading">Moja biblioteka</h2>

<form method="get" action="library.php" class="store-search-form">
    <input type="text" name="q" placeholder="Szukaj gry..." value="<?= htmlspecialchars($q) ?>">
    <select name="category_id">
        <option value="0">Wszystkie kategorie</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $category_id === (int)$cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Szukaj</button>
</form>

<?php if (empty($games)): ?>
    <p>Nie posiadasz jeszcze żadnych gier.</p>
    <a href="store.php">Przejdź do sklepu</a>
<?php else: ?>
    <div class="store-games-list">
    <?php foreach ($games as $game): ?>
        <div class="game-card store-game-card">
            <img src="<?= htmlspecialchars($game['image'] ?? 'assets/images/noimage.png') ?>" alt="okładka gry" class="store-game-img"><br>
            <strong>
            <a href="game.php?id=<?= $game['id'] ?>">
             <?= htmlspecialchars($game['title']) ?>
            </a>
            </strong>
            <small>Producent: <?= htmlspecialchars($game['developer']) ?></small><br>
            <button class="play-btn" data-title="<?= htmlspecialchars($game['title']) ?>">Graj</button>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="/game_library/assets/js/main.js"></script>
<div id="toast" style="display:none; position:fixed; bottom:40px; left:50%; transform:translateX(-50%); background:#222; color:#fff; padding:16px 30px; border-radius:8px; font-size:1.1em; z-index:9999; box-shadow:0 4px 16px #0003;"></div>

<?php
require_once 'includes/footer.php';
?>
