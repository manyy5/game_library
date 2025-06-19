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

// Sprawdź, czy gra już jest w bibliotece
$stmt = $pdo->prepare("SELECT id FROM purchases WHERE user_id = ? AND game_id = ?");
$stmt->execute([$user_id, $game_id]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Już posiadasz tę grę!']);
    exit;
}

// Pobierz cenę gry
$stmt = $pdo->prepare("SELECT price FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$price = $stmt->fetchColumn();
if ($price === false) {
    echo json_encode(['success' => false, 'message' => 'Nie znaleziono gry.']);
    exit;
}

// Pobierz saldo portfela użytkownika
$stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$balance = $stmt->fetchColumn();

if ($balance < $price) {
    echo json_encode(['success' => false, 'message' => 'Za mało środków w portfelu!']);
    exit;
}

// Odejmij cenę gry z portfela
$stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?");
$stmt->execute([$price, $user_id]);

// Dodaj zakup
$stmt = $pdo->prepare("INSERT INTO purchases (user_id, game_id) VALUES (?, ?)");
$stmt->execute([$user_id, $game_id]);

// Usuń grę z wishlisty (jeśli była tam dodana)
$stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND game_id = ?");
$stmt->execute([$user_id, $game_id]);

// Dodaj transakcję do historii portfela
$stmt = $pdo->prepare("SELECT title FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game_title = $stmt->fetchColumn();

$stmt = $pdo->prepare("INSERT INTO wallet_transactions (user_id, type, amount, description) VALUES (?, 'purchase', ?, ?)");
$stmt->execute([$user_id, -$price, 'Zakup gry: ' . $game_title]);

echo json_encode(['success' => true, 'message' => 'Gra została dodana do Twojej biblioteki!']);
