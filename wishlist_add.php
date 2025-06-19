<?php
require_once 'includes/db.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'] ?? null;
$game_id = $_POST['game_id'] ?? null;

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

function send_json($arr) {
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

if (!$user_id || !$game_id) {
    if ($isAjax) {
        send_json(['success' => false, 'message' => 'Błąd danych wejściowych.']);
    } else {
        $_SESSION['wishlist_msg'] = 'Błąd danych wejściowych.';
        header("Location: store.php");
        exit;
    }
}

// Sprawdź, czy gra już jest kupiona
$stmt = $pdo->prepare("SELECT id FROM purchases WHERE user_id = ? AND game_id = ?");
$stmt->execute([$user_id, $game_id]);
if ($stmt->fetch()) {
    if ($isAjax) {
        send_json(['success' => false, 'message' => 'Już posiadasz tę grę!']);
    } else {
        $_SESSION['wishlist_msg'] = 'Już posiadasz tę grę!';
        header("Location: store.php");
        exit;
    }
}

// Sprawdź, czy gra już jest na wishliście
$stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND game_id = ?");
$stmt->execute([$user_id, $game_id]);
if ($stmt->fetch()) {
    if ($isAjax) {
        send_json(['success' => false, 'message' => 'Gra już jest na wishliście!']);
    } else {
        $_SESSION['wishlist_msg'] = 'Gra już jest na wishliście!';
        header("Location: store.php");
        exit;
    }
}

// Dodaj do wishlisty
$stmt = $pdo->prepare("INSERT INTO wishlist (user_id, game_id) VALUES (?, ?)");
$stmt->execute([$user_id, $game_id]);

if ($isAjax) {
    send_json(['success' => true, 'message' => 'Gra została dodana do listy życzeń!']);
} else {
    $_SESSION['wishlist_msg'] = 'Gra została dodana do listy życzeń!';
    header("Location: store.php");
    exit;
}
?>
