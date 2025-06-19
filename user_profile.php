<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

$user_id = intval($_GET['id'] ?? 0);
if (!$user_id) {
    header("Location: users.php");
    exit;
}

// Pobierz dane użytkownika
$stmt = $pdo->prepare("SELECT username, avatar, created_at, description, favorite_game_id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<h2>Użytkownik nie znaleziony</h2>";
    echo '<a href="users.php">Powrót do listy użytkowników</a>';
    require_once 'includes/footer.php';
    exit;
}

// Pobierz recenzje użytkownika
$stmt = $pdo->prepare("
    SELECT r.*, g.title 
    FROM reviews r
    JOIN games g ON r.game_id = g.id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
    LIMIT 10
");
$stmt->execute([$user_id]);
$reviews = $stmt->fetchAll();

// Funkcja zwracająca kolor na podstawie oceny
function ratingColor($rating) {
    if ($rating >= 1 && $rating <= 2) return 'red';
    if ($rating >= 3 && $rating <= 4) return 'orange';
    if ($rating >= 5 && $rating <= 6) return 'black';
    if ($rating >= 7 && $rating <= 8) return 'green';
    if ($rating >= 9 && $rating <= 10) return 'gold';
    return 'black';
}

// Liczba posiadanych gier
$stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE user_id = ?");
$stmt->execute([$user_id]);
$games_count = $stmt->fetchColumn();

// Liczba napisanych recenzji
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
$stmt->execute([$user_id]);
$reviews_count = $stmt->fetchColumn();

?>

<div class="container" style="max-width: 540px;">
    <h2 class="centered-heading" style="margin-bottom: 16px;">
        Profil użytkownika: <?= htmlspecialchars($user['username']) ?>
    </h2>
    <div style="display: flex; flex-direction: column; align-items: center;">
        <img src="<?= htmlspecialchars($user['avatar'] ?? 'assets/images/noavatar.jpg') ?>" alt="avatar" class="profile-avatar" style="margin-bottom: 12px; border: 3px solid #a259ff;">
        <span style="color: #888; margin-bottom: 12px;">Dołączył: <?= htmlspecialchars($user['created_at']) ?></span>
    </div>

    <div style="background: linear-gradient(135deg, #232136 90%, #a259ff10 100%); border-radius: 12px; box-shadow: 0 2px 16px #000a; padding: 22px 26px; margin: 0 auto 26px auto; max-width: 420px; text-align: center;">
        <p style="margin-bottom: 12px;">
            <strong>Opis:</strong><br>
            <?= $user['description'] ? nl2br(htmlspecialchars($user['description'])) : '<em>Brak opisu</em>' ?>
        </p>
        <?php
       // Wyświetl ulubioną grę użytkownika
    if ($user['favorite_game_id']) {
        $stmt = $pdo->prepare("SELECT id, title, image FROM games WHERE id = ?");
        $stmt->execute([$user['favorite_game_id']]);
        $fav_game = $stmt->fetch();
        if ($fav_game):
    ?>
        <div style="margin-bottom: 14px;">
            <strong>Ulubiona gra:</strong><br>
            <a href="game.php?id=<?= $fav_game['id'] ?>" style="color:#b983ff; font-weight:bold; text-decoration:none;">
                <?= htmlspecialchars($fav_game['title']) ?>
            </a><br>
            <img src="<?= htmlspecialchars($fav_game['image'] ?? 'assets/images/noimage.png') ?>" alt="okładka gry" class="favorite-game-cover" style="margin-top:8px;">
        </div>
    <?php
        endif;
    }
        ?>
        <div>
            <strong>Statystyki:</strong>
            <ul style="margin: 7px 0 0 0; padding: 0; list-style: none;">
                <li>Posiadane gry: <span style="color:#00b894; font-weight:500;"><?= $games_count ?></span></li>
                <li>Napisane recenzje: <span style="color:#ffd700; font-weight:500;"><?= $reviews_count ?></span></li>
            </ul>
        </div>
    </div>

    <hr style="margin: 32px 0; border: none; border-top: 2px solid #3a2a4d;">

    <h3 class="centered-heading">Ostatnie recenzje</h3>
    <?php if (empty($reviews)): ?>
        <p style="text-align:center;">Ten użytkownik nie napisał jeszcze żadnych recenzji.</p>
    <?php else: ?>
        <ul class="latest-reviews" style="max-width: 600px; margin: 0 auto;">
        <?php foreach ($reviews as $review): ?>
            <li class="review-list-item">
                <strong>
                    <a href="game.php?id=<?= $review['game_id'] ?>">
                        <?= htmlspecialchars($review['title']) ?>
                    </a>
                </strong>
                — <span class="review-date"><?= htmlspecialchars($review['created_at']) ?></span><br>
                <span class="rating <?= ratingColor($review['rating']) ?>">
                    <?= (int)$review['rating'] ?>/10
                </span>
                <span class="review-content"><?= nl2br(htmlspecialchars($review['content'])) ?></span>
                <?php if ($review['image']): ?>
                    <br><img src="<?= htmlspecialchars($review['image']) ?>" alt="zdjęcie recenzji" class="review-image">
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div style="text-align:center; margin-top: 24px;">
        <a href="users.php">← Powrót do listy użytkowników</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
