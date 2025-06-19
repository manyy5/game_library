<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND game_id = ?");
$stmt->execute([$user_id, $game_id]);

header("Location: wishlist.php");
exit;
