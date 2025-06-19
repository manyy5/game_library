<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

$game_id = intval($_GET['id'] ?? 0);
if (!$game_id) {
    echo "<p>Nieprawidłowa gra.</p>";
    require_once 'includes/footer.php';
    exit;
}

// 1. Pobierz dane gry
$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch();

if (!$game) {
    echo "<p>Gra nie została znaleziona.</p>";
    require_once 'includes/footer.php';
    exit;
}

// 2. Pobierz wszystkie kategorie przypisane do gry
$stmt = $pdo->prepare("
    SELECT c.id, c.name 
    FROM categories c
    JOIN game_categories gc ON c.id = gc.category_id
    WHERE gc.game_id = ?
");
$stmt->execute([$game_id]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Średnia ocena
$stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as count_reviews FROM reviews WHERE game_id = ?");
$stmt->execute([$game_id]);
$review_stats = $stmt->fetch();

// Lista recenzji
$stmt = $pdo->prepare("
    SELECT r.*, u.username, u.avatar, u.id as user_id 
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.game_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$game_id]);
$reviews = $stmt->fetchAll();

$can_review = false;
$has_game = false;
$has_review = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM purchases WHERE user_id = ? AND game_id = ?");
    $stmt->execute([$_SESSION['user_id'], $game_id]);
    $has_game = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND game_id = ?");
    $stmt->execute([$_SESSION['user_id'], $game_id]);
    $has_review = $stmt->fetch();

    $can_review = $has_game && !$has_review;
}

// Obsługa dodawania recenzji
if (isset($_POST['add_review']) && $can_review) {
    $rating = intval($_POST['rating'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $image = null;

    // Obsługa uploadu zdjęcia
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $filename = uniqid().basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check && in_array($imageFileType, ['jpg','jpeg','png','gif', 'webp', 'jfif'])) {
            if ($_FILES["image"]["size"] < 2*1024*1024) { // max 2MB
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image = $target_file;
            }
        }
    }

    if ($rating >= 1 && $rating <= 10 && $content !== '') {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, game_id, rating, content, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $game_id, $rating, $content, $image]);
        header("Location: game.php?id=$game_id");
        exit;
    } else {
        echo "<p style='color:red'>Wypełnij poprawnie wszystkie pola recenzji!</p>";
    }
}

// Funkcja zwracająca kolor na podstawie oceny
function ratingColor($rating) {
    if ($rating >= 1 && $rating <= 2) return 'red';
    if ($rating >= 3 && $rating <= 4) return 'orange';
    if ($rating >= 5 && $rating <= 6) return 'black';
    if ($rating >= 7 && $rating <= 8) return 'green';
    if ($rating >= 9 && $rating <= 10) return 'gold';
    return 'black';
}

// Obsługa usuwania recenzji
if (isset($_GET['delete_review']) && isset($_SESSION['user_id'])) {
    $review_id = intval($_GET['delete_review']);
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$review_id, $_SESSION['user_id']]);
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
        header("Location: game.php?id=$game_id");
        exit;
    }
}

// Obsługa trybu edycji recenzji
$edit_review = null;
if (isset($_GET['edit_review']) && isset($_SESSION['user_id'])) {
    $edit_id = intval($_GET['edit_review']);
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$edit_id, $_SESSION['user_id']]);
    $edit_review = $stmt->fetch();
}

// Obsługa zapisu edytowanej recenzji
if (isset($_POST['edit_review_save']) && $edit_review) {
    $rating = intval($_POST['rating'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $image = $edit_review['image'];

    // Obsługa uploadu nowego zdjęcia (opcjonalnie)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $filename = uniqid().basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check && in_array($imageFileType, ['jpg','jpeg','png','gif', 'webp', 'jfif'])) {
            if ($_FILES["image"]["size"] < 2*1024*1024) {
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image = $target_file;
            }
        }
    }

    if ($rating >= 1 && $rating <= 10 && $content !== '') {
        $stmt = $pdo->prepare("UPDATE reviews SET rating=?, content=?, image=? WHERE id=? AND user_id=?");
        $stmt->execute([$rating, $content, $image, $edit_review['id'], $_SESSION['user_id']]);
        header("Location: game.php?id=$game_id");
        exit;
    } else {
        echo "<p style='color:red'>Wypełnij poprawnie wszystkie pola recenzji!</p>";
    }
}
?>

<div class="container" style="max-width: 700px; margin: 60px auto;">
    <h2 class="centered-heading"><?= htmlspecialchars($game['title']) ?></h2>
    <div class="game-details-center" style="display:flex; flex-direction:column; align-items:center; margin-bottom: 24px;">
        <img src="<?= htmlspecialchars($game['image'] ?? 'assets/images/noimage.png') ?>" alt="okładka gry" class="favorite-game-cover" style="margin-bottom: 18px;">
        
        <p style="text-align:center; margin-bottom:8px;"><strong>Kategorie:</strong>
        <?php if ($categories): ?>
            <?php foreach ($categories as $i => $cat): ?>
                <a href="store.php?category_id=<?= $cat['id'] ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a><?= $i < count($categories)-1 ? ', ' : '' ?>
            <?php endforeach; ?>
        <?php else: ?>
            Brak
        <?php endif; ?>
        </p>

        <p style="text-align:center; margin-bottom:8px;"><strong>Producent:</strong> <?= htmlspecialchars($game['developer']) ?></p>
        <p style="text-align:center; margin-bottom:8px;"><strong>Data wydania:</strong> <?= htmlspecialchars($game['release_date']) ?></p>
        <p style="text-align:center; margin-bottom:8px;"><strong>Cena:</strong> <?= number_format($game['price'], 2) ?> zł</p>
        <p style="text-align:center; margin-bottom:8px;">
            <strong>Średnia ocena:</strong>
            <?= $review_stats['count_reviews'] ? number_format($review_stats['avg_rating'], 2) . " / 10 (" . $review_stats['count_reviews'] . " recenzji)" : "Brak ocen" ?>
        </p>
    </div>

    <p  style="text-align:center; margin-bottom:8px;"><strong>Opis:</strong> <?= nl2br(htmlspecialchars($game['description'])) ?></p>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div style="display: flex; justify-content: center; gap: 18px; margin-bottom: 20px;">
        <form method="post" action="ajax/buy_game.php" class="buy-form inline-form" data-gameid="<?= $game['id'] ?>">
            <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
            <button type="submit" class="play-btn">Kup</button>
        </form>
        <form method="post" action="wishlist_add.php" class="wishlist-form inline-form" data-gameid="<?= $game['id'] ?>">
            <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
            <button type="submit" class="play-btn">Dodaj do listy życzeń</button>
        </form>
    </div>
<?php else: ?>
    <div style="text-align: center; margin-bottom: 20px;">
        <em>Zaloguj się, aby kupić lub dodać do listy życzeń</em>
    </div>
<?php endif; ?>

    <hr>
    <h3 class="centered-heading">Recenzje</h3>
    <?php if (empty($reviews)): ?>
        <p style="text-align:center;">Brak recenzji tej gry.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-list-item" style="margin-bottom:14px;">
                <img src="<?= htmlspecialchars($review['avatar'] ?? 'assets/images/noavatar.jpg') ?>"
                     alt="avatar" class="review-image" style="width:40px; height:40px; border-radius:50%; object-fit:cover; vertical-align:middle; margin-right:8px;">
                <strong><?= htmlspecialchars($review['username']) ?></strong>
                <span class="rating <?= ratingColor($review['rating']) ?>" style="font-size:1.2em;">
                    <?= (int)$review['rating'] ?>/10
                </span>
                <small class="review-date"><?= htmlspecialchars($review['created_at']) ?></small><br>
                <span class="review-content"><?= nl2br(htmlspecialchars($review['content'])) ?></span><br>
                <?php if ($review['image']): ?>
                    <img src="<?= htmlspecialchars($review['image']) ?>" alt="zdjęcie recenzji" class="review-image" style="max-width:200px; margin-top:5px;">
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
                    <br>
                    <a href="game.php?id=<?= $game_id ?>&edit_review=<?= $review['id'] ?>">Edytuj</a>
                    <a href="game.php?id=<?= $game_id ?>&delete_review=<?= $review['id'] ?>" onclick="return confirm('Na pewno usunąć recenzję?')">Usuń</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($edit_review): ?>
    <h3 class="centered-heading" style="text-align:center; margin-bottom:18px;">Edytuj swoją recenzję</h3>
    <form method="post" enctype="multipart/form-data" class="profile-form" style="max-width:400px; margin:0 auto 18px auto; display:flex; flex-direction:column; gap:10px;">
        <input type="number" name="rating" min="1" max="10" required value="<?= (int)$edit_review['rating'] ?>" class="profile-input" style="width:100%; margin-bottom:8px;" placeholder="Ocena (1-10)">
        <textarea name="content" rows="4" class="profile-input" required style="width:100%; margin-bottom:8px;" placeholder="Treść recenzji"><?= htmlspecialchars($edit_review['content']) ?></textarea>
        <div style="margin-bottom:8px;">
            <span style="font-size:0.95em; color:#888;">Aktualne zdjęcie:</span><br>
            <?php if ($edit_review['image']): ?>
                <img src="<?= htmlspecialchars($edit_review['image']) ?>" class="review-image" style="max-width:150px; margin:8px 0; display:block;">
            <?php endif; ?>
        </div>
        <label style="margin-bottom:4px; color:#888;">Nowe zdjęcie (opcjonalnie):</label>
        <input type="file" name="image" accept="image/*" class="profile-input" style="width:100%; margin-bottom:8px;">
        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" name="edit_review_save" class="play-btn" style="flex:1;">Zapisz zmiany</button>
            <a href="game.php?id=<?= $game_id ?>" class="play-btn" style="flex:1; text-align:center; display:inline-block; line-height:38px;">Anuluj</a>
        </div>
    </form>
<?php endif; ?>
<hr style="max-width:400px; margin:24px auto;">

<?php if ($can_review): ?>
    <h3 class="centered-heading" style="text-align:center; margin-bottom:18px;">Dodaj swoją recenzję</h3>
    <form method="post" enctype="multipart/form-data" class="profile-form" style="max-width:400px; margin:0 auto 18px auto; display:flex; flex-direction:column; gap:10px;">
        <input type="number" name="rating" min="1" max="10" required class="profile-input" style="width:100%; margin-bottom:8px;" placeholder="Ocena (1-10)">
        <textarea name="content" rows="4" class="profile-input" required style="width:100%; margin-bottom:8px;" placeholder="Treść recenzji"></textarea>
        <label style="margin-bottom:4px; color:#888;">Zdjęcie (opcjonalnie):</label>
        <input type="file" name="image" accept="image/*" class="profile-input" style="width:100%; margin-bottom:8px;">
        <button type="submit" name="add_review" class="play-btn" style="width:100%;">Dodaj recenzję</button>
    </form>

    <?php elseif (!isset($_SESSION['user_id'])): ?>
        <p><em>Zaloguj się i kup grę, aby dodać recenzję.</em></p>
    <?php elseif (!$has_game): ?>
        <p><em>Musisz posiadać tę grę, aby dodać recenzję.</em></p>
    <?php elseif ($has_review): ?>
        <p><em>Już dodałeś recenzję tej gry.</em></p>
    <?php endif; ?>
</div>
<script src="assets/js/main.js"></script>
<?php require_once 'includes/footer.php'; ?>
