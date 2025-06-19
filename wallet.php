<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = '';
$amount = 0; // inicjalizacja

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);
    if ($amount > 0) {
        $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);
        $stmt = $pdo->prepare("INSERT INTO wallet_transactions (user_id, type, amount, description) VALUES (?, 'deposit', ?, 'Doładowanie portfela')");
        $stmt->execute([$user_id, $amount]);
        $_SESSION['msg'] = "Portfel doładowany o " . number_format($amount, 2) . " zł!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $msg = "Podaj poprawną kwotę doładowania.";
    }
}

// Pobierz komunikat po przekierowaniu
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// Pobierz aktualny stan portfela
$stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$wallet = $stmt->fetchColumn();
?>

<div class="container" style="max-width: 600px;">
    <h2 class="centered-heading">Portfel</h2>
    <p class="centered-heading">Twój stan portfela: <strong><?= number_format($wallet, 2) ?> zł</strong></p>
    <?php if ($msg): ?><p class="centered-heading" style="color:green;"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
    <form method="post">
        Kwota doładowania: <input type="number" name="amount" min="1" step="0.01" required class="deposit-amount"> zł
        <button type="submit" class="deposit-btn">Doładuj portfel</button>
    </form>

    <h3 class="centered-heading">Historia transakcji</h3>
    <table border="1" cellpadding="6" style="border-collapse:collapse;">
        <tr class="centered-heading">
            <th>Data</th>
            <th>Typ</th>
            <th>Opis</th>
            <th>Kwota</th>
        </tr>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        foreach ($stmt as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= $row['type'] === 'deposit' ? 'Doładowanie' : 'Zakup' ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td style="color:<?= $row['amount'] >= 0 ? 'green' : 'red' ?>;">
                    <?= number_format($row['amount'], 2) ?> zł
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
