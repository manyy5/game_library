<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$game_id = intval($_GET['id'] ?? 0);
if (!$game_id) {
    echo "<p>Nieprawidłowe ID gry.</p>";
    require_once '../includes/footer.php';
    exit;
}

// Pobierz dane gry
$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch();
if (!$game) {
    echo "<p>Gra nie znaleziona.</p>";
    require_once '../includes/footer.php';
    exit;
}

// Pobierz wszystkie kategorie
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();

// Pobierz kategorie przypisane do gry
$stmt = $pdo->prepare("SELECT category_id FROM game_categories WHERE game_id = ?");
$stmt->execute([$game_id]);
$game_cats = $stmt->fetchAll(PDO::FETCH_COLUMN);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $developer = trim($_POST['developer'] ?? '');
    $release_date = $_POST['release_date'] ?? null;
    $image = $game['image'];

    // Obsługa uploadu nowej okładki
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $filename = uniqid().basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check && in_array($imageFileType, ['jpg','jpeg','png','gif', 'webp', 'jfif'])) {
            if ($_FILES["image"]["size"] < 2*1024*1024) {
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image = "assets/images/".$filename;
            }
        }
    }

    if ($title && $price > 0) {
        $stmt = $pdo->prepare("UPDATE games SET title=?, description=?, price=?, image=?, developer=?, release_date=? WHERE id=?");
        $stmt->execute([$title, $description, $price, $image, $developer, $release_date, $game_id]);
        
        // Aktualizuj kategorie gry
        $selected_cats = $_POST['categories'] ?? [];
        $stmt = $pdo->prepare("DELETE FROM game_categories WHERE game_id = ?");
        $stmt->execute([$game_id]);
        if (!empty($selected_cats)) {
            $stmt = $pdo->prepare("INSERT INTO game_categories (game_id, category_id) VALUES (?, ?)");
            foreach ($selected_cats as $cat_id) {
                $stmt->execute([$game_id, $cat_id]);
            }
        }

        $msg = "Gra została zaktualizowana!";
        // Odśwież dane gry i kategorie
        $stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT category_id FROM game_categories WHERE game_id = ?");
        $stmt->execute([$game_id]);
        $game_cats = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
        $msg = "Uzupełnij poprawnie wszystkie wymagane pola.";
    }
}
?>

<style>
<?php include '../assets/css/style.css'; ?>
</style>

<h2>Edytuj grę</h2>
<?php if ($msg): ?><p style="color:green;"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data">
    Tytuł: <input type="text" name="title" value="<?= htmlspecialchars($game['title']) ?>" required><br>
    Opis: <textarea name="description"><?= htmlspecialchars($game['description']) ?></textarea><br>
    Cena: <input type="number" name="price" step="0.01" min="0" value="<?= $game['price'] ?>" required><br>
    Producent: <input type="text" name="developer" value="<?= htmlspecialchars($game['developer']) ?>"><br>
    Data wydania: <input type="date" name="release_date" value="<?= htmlspecialchars($game['release_date']) ?>"><br>
    Aktualna okładka:<br>
    <img src="../<?= htmlspecialchars($game['image'] ?? 'assets/images/noimage.png') ?>" style="width:120px;"><br>
    Nowa okładka (opcjonalnie): <input type="file" name="image" accept="image/*"><br>
    <p><strong>Kategorie:</strong></p>
    <?php foreach ($categories as $cat): ?>
        <label>
            <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>"
                <?= in_array($cat['id'], $game_cats) ? 'checked' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
        </label><br>
    <?php endforeach; ?>
    <button type="submit">Zapisz zmiany</button>
</form>
<a href="games.php">Powrót</a>
<?php require_once '../includes/footer.php'; ?>
