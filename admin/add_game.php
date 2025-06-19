<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $developer = trim($_POST['developer'] ?? '');
    $release_date = $_POST['release_date'] ?? null;
    $image = null;

    // Obsługa uploadu okładki
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        $filename = uniqid().basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check && in_array($imageFileType, ['jpg','jpeg','png','gif'])) {
            if ($_FILES["image"]["size"] < 2*1024*1024) {
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image = "assets/images/".$filename;
            }
        }
    }

    if ($title && $price > 0) {
        $stmt = $pdo->prepare("INSERT INTO games (title, description, price, image, developer, release_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $image, $developer, $release_date]);
        $msg = "Gra została dodana!";
    } else {
        $msg = "Uzupełnij poprawnie wszystkie wymagane pola.";
    }
}
?>

<style>
<?php include '../assets/css/style.css'; ?>
</style>

<h2>Dodaj nową grę</h2>
<?php if ($msg): ?><p style="color:green;"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data">
    Tytuł: <input type="text" name="title" required><br>
    Opis: <textarea name="description"></textarea><br>
    Cena: <input type="number" name="price" step="0.01" min="0" required><br>
    Producent: <input type="text" name="developer"><br>
    Data wydania: <input type="date" name="release_date"><br>
    Okładka (jpg/png/gif): <input type="file" name="image" accept="image/*"><br>
    <button type="submit">Dodaj grę</button>
</form>
<a href="games.php">Powrót</a>
<?php require_once '../includes/footer.php'; ?>
