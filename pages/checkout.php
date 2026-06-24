<?php
session_start();
require_once '../includes/db.php';

$winkelmand = $_SESSION['winkelmand'] ?? [];

// Als winkelmand leeg is stuurt het terug
if (empty($winkelmand)) {
    header('Location: winkelmand.php');
    exit;
}

// Prijzen berekenen
$subtotaal = 0;
foreach ($winkelmand as $item) {
    $subtotaal += $item['prijs'];
}
$btw = $subtotaal * 0.21;
$totaal = $subtotaal + $btw;

$melding = '';

// Bestelling verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bestel'])) {
    $naam = trim($_POST['naam']);
    $email = trim($_POST['email']);

    if (!empty($naam) && !empty($email)) {
        // Sla de bestelling op in de bestellingen tabel
        $stmt = $pdo->prepare("INSERT INTO bestellingen (naam, email, subtotaal, btw, totaal) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$naam, $email, $subtotaal, $btw, $totaal]);

        // Haalt het zojuist ingevoegde ID op
        $bestellingId = $pdo->lastInsertId();

        // Slaat elk domein apart op
        foreach ($winkelmand as $item) {
            $stmt2 = $pdo->prepare("INSERT INTO bestelling_domeinen (bestelling_id, domein, prijs) VALUES (?, ?, ?)");
            $stmt2->execute([$bestellingId, $item['domein'], $item['prijs']]);
        }

        // Winkelmand leegmaken na bestelling
        $_SESSION['winkelmand'] = [];

        header('Location: bestellingen.php?succes=1');
        exit;
    } else {
        $melding = 'Vul alle velden in.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="/domein-zoeker-minty-media/assets/css/style.css">
</head>
<body>

<nav>
    <span>Domein Zoeker</span>
    <a href="../index.php">Zoeken</a>
    <a href="winkelmand.php">Winkelmand (<?= count($winkelmand) ?>)</a>
    <a href="bestellingen.php">Bestellingen</a>
</nav>

<div class="container">
    <h1>Checkout</h1>

    <?php if ($melding): ?>
        <div class="melding fout"><?= htmlspecialchars($melding) ?></div>
    <?php endif; ?>

    <!-- Overzicht van wat je bestelt -->
    <h2 style="font-size:17px; margin-bottom:10px;">Jouw domeinen:</h2>
    <table>
        <thead>
            <tr>
                <th>Domein</th>
                <th>Prijs/jaar</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($winkelmand as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['domein']) ?></td>
                <td>€<?= number_format($item['prijs'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Prijsoverzicht -->
    <div class="winkelmand-info">
        <p>Subtotaal: <strong>€<?= number_format($subtotaal, 2, ',', '.') ?></strong></p>
        <p>BTW (21%): <strong>€<?= number_format($btw, 2, ',', '.') ?></strong></p>
        <p>Totaal incl. BTW: <strong>€<?= number_format($totaal, 2, ',', '.') ?></strong></p>
    </div>

    <br>

    <!-- Gegevens formulier -->
    <h2 style="font-size:17px; margin-bottom:10px;">Jouw gegevens:</h2>
    <form method="POST" action="checkout.php">
        <div class="form-groep">
            <label for="naam">Naam:</label>
            <input type="text" id="naam" name="naam" placeholder="Jan Jansen" required>
        </div>
        <div class="form-groep">
            <label for="email">E-mailadres:</label>
            <input type="email" id="email" name="email" placeholder="jan@voorbeeld.nl" required>
        </div>
        <button type="submit" name="bestel" class="btn-bestel">Bestelling plaatsen</button>
    </form>
</div>

</body>
</html>