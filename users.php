<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Pobierz parametry wyszukiwania
$q = trim($_GET['q'] ?? '');

// Buduj zapytanie SQL z wyszukiwaniem po nazwie użytkownika
$sql = "SELECT id, username, avatar, created_at FROM users WHERE 1=1";
$params = [];

if ($q) {
    $sql .= " AND username LIKE ?";
    $params[] = '%' . $q . '%';
}
$sql .= " ORDER BY username ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>

<h2 class="centered-heading">Użytkownicy</h2>

<!-- Wyszukiwarka użytkowników -->
<form method="get" action="users.php" class="store-search-form">
    <input type="text" name="q" placeholder="Szukaj użytkownika..." value="<?= htmlspecialchars($q) ?>">
    <button type="submit">Szukaj</button>
</form>

<?php if (empty($users)): ?>
    <p>Nie znaleziono użytkowników.</p>
<?php else: ?>
    <div class="new-users">
    <?php foreach ($users as $user): ?>
        <div class="user-card">
            <img src="<?= htmlspecialchars($user['avatar'] ?? 'assets/images/noavatar.jpg') ?>" alt="avatar">
            <div class="user-name">
                <a href="user_profile.php?id=<?= $user['id'] ?>">
                    <?= htmlspecialchars($user['username']) ?>
                </a>
            </div>
            <div class="user-date">
                Dołączył: <?= date('Y-m-d', strtotime($user['created_at'])) ?>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
