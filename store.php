<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Pobierz kategorie do filtrowania
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();

// Pobierz parametry wyszukiwania i filtrowania
$q = trim($_GET['q'] ?? '');
$category_id = intval($_GET['category_id'] ?? 0);

// Buduj zapytanie SQL dynamicznie (wiele kategorii na grę)
$sql = "SELECT DISTINCT g.* FROM games g";
$params = [];

if ($category_id) {
    $sql .= " INNER JOIN game_categories gc ON g.id = gc.game_id WHERE gc.category_id = ?";
    $params[] = $category_id;
    if ($q) {
        $sql .= " AND g.title LIKE ?";
        $params[] = '%' . $q . '%';
    }
} else if ($q) {
    $sql .= " WHERE g.title LIKE ?";
    $params[] = '%' . $q . '%';
}
$sql .= " ORDER BY g.title ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$games = $stmt->fetchAll();
?>

<?php
if (isset($_SESSION['wishlist_msg'])) {
    echo '<div class="alert">'.htmlspecialchars($_SESSION['wishlist_msg']).'</div>';
    unset($_SESSION['wishlist_msg']);
}
?>

<h2 class="centered-heading">Sklep</h2>


<!-- Wyszukiwarka i filtrowanie -->
<form method="get" action="store.php" class="store-search-form">
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
    <p>Brak gier spełniających kryteria wyszukiwania.</p>
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
            Cena: <?= number_format($game['price'], 2) ?> zł<br>
            <small>Producent: <?= htmlspecialchars($game['developer']) ?></small><br>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="ajax/buy_game.php" class="buy-form" data-gameid="<?= $game['id'] ?>">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <button type="submit">Kup</button>
                </form>
            <?php else: ?>
                <em>Zaloguj się, aby kupić</em>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="wishlist_add.php" class="wishlist-form inline-form" data-gameid="<?= $game['id'] ?>">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <button type="submit">Dodaj do listy życzeń</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="assets/js/main.js"></script>
<?php
require_once 'includes/footer.php';
?>
