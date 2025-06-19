<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT g.* FROM games g
    JOIN wishlist w ON g.id = w.game_id
    WHERE w.user_id = ?
    ORDER BY g.title ASC
");
$stmt->execute([$user_id]);
$games = $stmt->fetchAll();
?>

<div class="container" style="max-width: 600px;">
    <h2 class="centered-heading">Twoja lista życzeń</h2>
    <?php if (empty($games)): ?>
        <p style="text-align:center;">Brak gier na liście życzeń.</p>
    <?php else: ?>
        <ul style="padding-left:0; list-style:none; margin:0 auto; max-width:440px;">
        <?php foreach ($games as $game): ?>
            <li style="margin-bottom:16px; display:flex; align-items:center; justify-content:center; gap:14px;">
                <img src="<?= htmlspecialchars($game['image'] ?? 'assets/images/noimage.png') ?>" alt="okładka" class="review-image" style="width:40px; height:40px; object-fit:cover; border-radius:8px;">
                <strong>
                    <a href="game.php?id=<?= $game['id'] ?>" style="color:#b983ff;">
                        <?= htmlspecialchars($game['title']) ?>
                    </a>
                </strong>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post" class="buy-form inline-form" data-gameid="<?= $game['id'] ?>" style="margin:0;">
                        <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                        <button type="submit" class="play-btn">Kup</button>
                    </form>
                <?php endif; ?>
                <form method="post" action="wishlist_remove.php" class="inline-form" style="margin:0;">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <button type="submit" class="delete-btn" onclick="return confirm('Usunąć tę grę z listy życzeń?')">Usuń</button>
                </form>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script src="assets/js/main.js"></script>
<?php require_once 'includes/footer.php'; ?>
