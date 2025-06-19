<?php
// Dane dostępowe do bazy
$host = 'localhost';
$db   = 'game_library'; // nazwa Twojej bazy danych
$user = 'root';         // domyślnie w XAMPP to 'root'
$pass = '';             // domyślnie w XAMPP hasło jest puste
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
    exit();
}
?>
