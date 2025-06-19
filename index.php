<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Najwyżej oceniane gry
$stmt = $pdo->query("
  SELECT g.id, g.title, g.image, AVG(r.rating) as avg_rating
  FROM games g
  JOIN reviews r ON g.id = r.game_id
  GROUP BY g.id
  HAVING COUNT(r.id) > 0
  ORDER BY avg_rating DESC
  LIMIT 6
");
$top_rated = $stmt->fetchAll();

$stmt = $pdo->query("
  SELECT r.content, r.rating, r.created_at, u.username, u.avatar, u.id as user_id, g.title, g.id as game_id
  FROM reviews r
  JOIN users u ON r.user_id = u.id
  JOIN games g ON r.game_id = g.id
  ORDER BY r.created_at DESC
  LIMIT 5
");
$latest_reviews = $stmt->fetchAll();

// Ostatnio dołączeni użytkownicy
$stmt = $pdo->query("SELECT id, username, avatar, created_at FROM users ORDER BY created_at DESC LIMIT 12");
$new_users = $stmt->fetchAll();

function ratingColor($rating) {
    if ($rating >= 1 && $rating <= 2) return 'red';
    if ($rating >= 3 && $rating <= 4) return 'orange';
    if ($rating >= 5 && $rating <= 6) return 'black';
    if ($rating >= 7 && $rating <= 8) return 'green';
    if ($rating >= 9 && $rating <= 10) return 'gold';
    return 'black';
}
?>
<div class="container">

<h1>
  <img src="assets/images/logo2.png" alt="Logo GameLib" class="logo">
  Witamy w GameLib!
</h1>
<p class="subtitle">Twoja biblioteka i sklep z grami. Odkrywaj nowości, czytaj recenzje i poznawaj społeczność graczy!</p>

<!-- Najwyżej oceniane gry -->
<h2>Najwyżej oceniane gry</h2>
<div class="top-rated-games">
<?php foreach (array_slice($top_rated, 0, 5) as $game):
 ?>
  <div class="game-card">
    <a href="game.php?id=<?= $game['id'] ?>">
      <img src="<?= htmlspecialchars($game['image']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
      <div class="game-title"><?= htmlspecialchars($game['title']) ?></div>
    </a>
    <div class="rating <?= ratingColor($game['avg_rating']) ?>">
      <?= number_format($game['avg_rating'],1) ?>/10
    </div>
  </div>
<?php endforeach; ?>
</div>

<h2>Ostatnie recenzje graczy</h2>
<ul class="latest-reviews">
<?php foreach ($latest_reviews as $rev): ?>
  <li style="display:flex; align-items:center; gap:12px;">
    <img src="<?= htmlspecialchars($rev['avatar'] ?? 'assets/images/noavatar.jpg') ?>"
         alt="avatar" class="review-image" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
    <div>
      <a href="user_profile.php?id=<?= $rev['user_id'] ?>"><strong><?= htmlspecialchars($rev['username']) ?></strong></a> o 
      <a href="game.php?id=<?= $rev['game_id'] ?>"><?= htmlspecialchars($rev['title']) ?></a>:
      <span class="rating <?= ratingColor($rev['rating']) ?>">
        <?= (int)$rev['rating'] ?>/10
      </span>
      <div class="review-content"><?= htmlspecialchars(mb_strimwidth($rev['content'],0,80,'...')) ?></div>
      <span class="review-date"><?= htmlspecialchars($rev['created_at']) ?></span>
    </div>
  </li>
<?php endforeach; ?>
</ul>

<!-- Ostatnio dołączeni użytkownicy -->
<h2>Ostatnio dołączeni użytkownicy</h2>
<div class="new-users">
<?php foreach ($new_users as $user): ?>
  <div class="user-card">
    <a href="user_profile.php?id=<?= $user['id'] ?>">
      <img src="<?= htmlspecialchars($user['avatar'] ?? 'assets/images/noavatar.jpg') ?>" alt="avatar">
      <div class="user-name"><?= htmlspecialchars($user['username']) ?></div>
    </a>
    <div class="user-date"><?= htmlspecialchars(date('Y-m-d', strtotime($user['created_at']))) ?></div>
  </div>
<?php endforeach; ?>
</div>
</div>

<?php
require_once 'includes/footer.php';
?>
