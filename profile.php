<?php
require_once 'includes/header.php';
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Pobierz dane użytkownika
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Pobierz gry użytkownika z biblioteki
$stmt = $pdo->prepare("
    SELECT g.id, g.title
    FROM games g
    JOIN purchases p ON g.id = p.game_id
    WHERE p.user_id = ?
    GROUP BY g.id
    ORDER BY g.title
");
$stmt->execute([$user_id]);
$user_games = $stmt->fetchAll();

// Pobierz recenzje użytkownika
$stmt = $pdo->prepare("
    SELECT r.*, g.title 
    FROM reviews r
    JOIN games g ON r.game_id = g.id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$user_id]);
$reviews = $stmt->fetchAll();

// Funkcja do kolorowania oceny
function ratingColor($rating) {
    if ($rating >= 1 && $rating <= 2) return 'red';
    if ($rating >= 3 && $rating <= 4) return 'orange';
    if ($rating >= 5 && $rating <= 6) return 'black';
    if ($rating >= 7 && $rating <= 8) return 'green';
    if ($rating >= 9 && $rating <= 10) return 'gold';
    return 'black';
}

// Zmiana hasła
$change_pass_msg = '';
if (isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $new_pass2 = $_POST['new_password2'] ?? '';

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $db_pass = $stmt->fetchColumn();

    if (!password_verify($old_pass, $db_pass)) {
        $change_pass_msg = "Stare hasło jest nieprawidłowe.";
    } elseif (strlen($new_pass) < 6) {
        $change_pass_msg = "Nowe hasło musi mieć co najmniej 6 znaków.";
    } elseif ($new_pass !== $new_pass2) {
        $change_pass_msg = "Nowe hasła się nie zgadzają.";
    } else {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $user_id]);
        $change_pass_msg = "Hasło zostało zmienione!";
    }
}

//Zmiana nicku
$nick_msg = '';
if (isset($_POST['change_nick'])) {
    $new_nick = trim($_POST['new_nick'] ?? '');
    if ($new_nick && $new_nick !== $user['username']) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$new_nick, $user_id]);
        if ($stmt->fetch()) {
            $nick_msg = "Ten nick jest już zajęty.";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->execute([$new_nick, $user_id]);
            $_SESSION['username'] = $new_nick;
            $nick_msg = "Nick został zmieniony!";
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    } else {
        $nick_msg = "Podaj nowy nick, inny niż obecny.";
    }
}

// Zmiana avatara
$avatar_msg = '';
if (isset($_POST['change_avatar']) && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $filename = uniqid().basename($_FILES["avatar"]["name"]);
    $target_file = $target_dir . $filename;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check && in_array($imageFileType, ['jpg','jpeg','png','gif'])) {
        if ($_FILES["avatar"]["size"] < 2*1024*1024) {
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
            $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->execute([$target_file, $user_id]);
            $avatar_msg = "Avatar został zmieniony!";
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        } else {
            $avatar_msg = "Plik jest za duży (max 2MB).";
        }
    } else {
        $avatar_msg = "Nieprawidłowy format pliku.";
    }
}

// Zmiana opisu i ulubionej gry
if (isset($_POST['update_description'])) {
    $desc = trim($_POST['description'] ?? $user['description'] ?? '');
    $fav_id = isset($_POST['favorite_game_id']) && $_POST['favorite_game_id'] !== '' ? intval($_POST['favorite_game_id']) : null;
    $valid_fav = null;
    if ($fav_id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE user_id = ? AND game_id = ?");
        $stmt->execute([$user_id, $fav_id]);
        if ($stmt->fetchColumn() > 0) {
            $valid_fav = $fav_id;
        }
    }
    $stmt = $pdo->prepare("UPDATE users SET description = ?, favorite_game_id = ? WHERE id = ?");
    $stmt->execute([$desc, $valid_fav, $user_id]);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}

// Pobierz tytuł i zdjęcie ulubionej gry
$fav_game_title = null;
$fav_game_image = null;
if ($user['favorite_game_id']) {
    $stmt = $pdo->prepare("
        SELECT g.title, g.image
        FROM games g
        JOIN purchases p ON g.id = p.game_id
        WHERE g.id = ? AND p.user_id = ?
        LIMIT 1
    ");
    $stmt->execute([$user['favorite_game_id'], $user_id]);
    $fav_game = $stmt->fetch();
    if ($fav_game) {
        $fav_game_title = $fav_game['title'];
        $fav_game_image = $fav_game['image'];
    }
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

<div class="container" style="max-width: 700px;">
    <div style="display: flex; flex-direction: column; align-items: center;">
        <h2 class="centered-heading" style="margin-bottom: 10px;">Twój profil</h2>
        <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 18px;">
            <img src="<?= htmlspecialchars($user['avatar'] ?? 'assets/images/noavatar.jpg') ?>" alt="avatar" class="profile-avatar" style="margin-bottom: 10px; border: 3px solid #a259ff;">
            <span style="font-size: 1.2em; color: #e0aaff; font-weight: 500;"><?= htmlspecialchars($user['username']) ?></span>
            <span style="color: #b6a1e6;"><?= htmlspecialchars($user['email']) ?></span>
            <span style="color: #888;">Dołączył: <?= htmlspecialchars($user['created_at']) ?></span>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, #232136 90%, #a259ff10 100%); border-radius: 12px; box-shadow: 0 2px 16px #000a; padding: 24px 28px; margin-bottom: 28px; max-width: 440px; margin-left:auto; margin-right:auto; text-align:center;">
    <p style="margin-bottom: 10px;"><strong>Opis:</strong><br>
        <?= $user['description'] ? nl2br(htmlspecialchars($user['description'])) : '<em>Brak opisu</em>' ?>
    </p>

    <div style="margin-bottom: 10px;">
    <strong>Ulubiona gra:</strong>
    <?php if ($fav_game_title && $user['favorite_game_id']): ?>
        <div style="margin-top:3px;">
            <a href="game.php?id=<?= (int)$user['favorite_game_id'] ?>" style="color:#b983ff; font-weight:bold; text-decoration:none;">
                <?= htmlspecialchars($fav_game_title) ?>
            </a>
            <?php if ($fav_game_image): ?>
                <img src="<?= htmlspecialchars($fav_game_image) ?>" alt="okładka gry" class="favorite-game-cover" style="margin-top:8px;">
            <?php endif; ?>
        </div>
    <?php else: ?>
        <em>Brak</em>
    <?php endif; ?>
</div>

    <div style="margin-bottom: 10px;">
        <strong>Statystyki:</strong>
        <ul style="margin: 7px 0 0 0; padding: 0; list-style: none;">
            <li>Posiadane gry: <span style="color:#00b894; font-weight:500;"><?= $games_count ?></span></li>
            <li>Napisane recenzje: <span style="color:#ffd700; font-weight:500;"><?= $reviews_count ?></span></li>
        </ul>
    </div>
</div>

    <!-- Formularz zmiany avatara -->
    <h3 class="centered-heading" style="margin-top: 30px;">Zmień avatar</h3>
    <?php if ($avatar_msg): ?>
        <p class="success-msg" style="text-align:center;"><?= htmlspecialchars($avatar_msg) ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="profile-form" style="justify-content:center; align-items:center; margin-bottom:10px;">
        <input type="file" name="avatar" accept="image/*" required style="margin-bottom:8px;">
        <button type="submit" name="change_avatar" class="play-btn">Zmień avatar</button>
    </form>

    <hr style="margin: 32px 0; border: none; border-top: 2px solid #3a2a4d;">

    <div style="display:flex; flex-wrap:wrap; gap: 32px; justify-content:center;">
        <div style="flex:1; min-width:260px; max-width:330px;">
            <h3 class="centered-heading">Zmień nick</h3>
            <?php if (!empty($nick_msg)): ?>
                <p class="success-msg" style="text-align:center;"><?= htmlspecialchars($nick_msg) ?></p>
            <?php endif; ?>
            <form method="post" class="profile-form" style="margin-bottom:10px;">
                <input type="text" name="new_nick" required value="<?= htmlspecialchars($user['username']) ?>" class="profile-input" style="margin-bottom:8px;">
                <button type="submit" name="change_nick" class="play-btn">Zmień nick</button>
            </form>
        </div>

       <div style="flex:1; min-width:260px; max-width:330px;">
    <h3 class="centered-heading">Zmiana opisu</h3>
    <form method="post" class="profile-form" style="margin-bottom:10px;">
        <textarea name="description" rows="3" class="profile-input" placeholder="Twój opis..." style="margin-bottom:8px;"><?= htmlspecialchars($user['description'] ?? '') ?></textarea>
        <label for="favorite_game_id" style="margin-top:10px; display:block;"><strong>Wybierz ulubioną grę:</strong></label>
        <select name="favorite_game_id" id="favorite_game_id" class="profile-input" style="margin-bottom:8px;">
            <option value="">-- wybierz --</option>
            <?php foreach ($user_games as $g): ?>
                <option value="<?= $g['id'] ?>" <?= ($user['favorite_game_id'] ?? '') == $g['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="update_description" class="play-btn">Zapisz</button>
    </form>
</div>

    <div style="max-width: 420px; margin: 0 auto 32px auto;">
        <h3 class="centered-heading">Zmień hasło</h3>
        <?php if ($change_pass_msg): ?>
            <p class="success-msg" style="text-align:center;"><?= htmlspecialchars($change_pass_msg) ?></p>
        <?php endif; ?>
        <form method="post" class="profile-form">
            <input type="password" name="old_password" required class="profile-input" placeholder="Stare hasło" style="margin-bottom:8px;">
            <input type="password" name="new_password" required class="profile-input" placeholder="Nowe hasło" style="margin-bottom:8px;">
            <input type="password" name="new_password2" required class="profile-input" placeholder="Powtórz nowe hasło" style="margin-bottom:8px;">
            <button type="submit" name="change_password" class="play-btn">Zmień hasło</button>
        </form>
    </div>

    <hr style="margin: 32px 0; border: none; border-top: 2px solid #3a2a4d;">

    <h3 class="centered-heading">Twoje recenzje</h3>
    <?php if (empty($reviews)): ?>
        <p style="text-align:center;">Nie zamieściłeś jeszcze żadnych recenzji.</p>
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
</div>

<?php require_once 'includes/footer.php'; ?>
