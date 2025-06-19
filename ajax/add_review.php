<?php
require_once '../includes/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Musisz być zalogowany!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$content = trim($_POST['content'] ?? '');

if ($rating < 1 || $rating > 10 || $content === '') {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe dane.']);
    exit;
}

// Czy użytkownik posiada grę?
$stmt = $pdo->prepare("SELECT id FROM purchases WHERE user_id = ? AND game_id = ?");
$stmt->execute([$user_id, $game_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Nie posiadasz tej gry.']);
    exit;
}

// Czy już dodał recenzję?
$stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND game_id = ?");
$stmt->execute([$user_id, $game_id]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Już dodałeś recenzję tej gry.']);
    exit;
}

// Dodaj recenzję (bez zdjęcia, bo upload plików wymaga klasycznego POST)
$stmt = $pdo->prepare("INSERT INTO reviews (user_id, game_id, rating, content) VALUES (?, ?, ?, ?)");
$stmt->execute([$user_id, $game_id, $rating, $content]);
echo json_encode(['success' => true, 'message' => 'Recenzja dodana!']);
