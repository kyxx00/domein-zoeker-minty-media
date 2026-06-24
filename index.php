<?php
session_start();
require_once 'includes/api.php';

// Alle TLD's die we willen zoeken
$extensies = ['com', 'nl', 'net', 'org', 'io', 'be', 'de', 'eu', 'info', 'shop', 'online', 'co'];

$resultaten = [];
$zoeknaam = '';

// Als het zoekformulier is ingediend
if (isset($_POST['domein'])) {
    $zoeknaam = trim($_POST['domein']);
    if (!empty($zoeknaam)) {
        $resultaten = zoekDomeinen($zoeknaam, $extensies);
    }
}

// Domein toevoegen aan winkelmand
if (isset($_POST['toevoegen'])) {
    $domein = $_POST['domein_naam'];
    $prijs = $_POST['domein_prijs'];

    // Zorgt ervoor dat de winkelmand array bestaat
    if (!isset($_SESSION['winkelmand'])) {
        $_SESSION['winkelmand'] = [];
    }

    // Domein opslaan in de sessie
    $_SESSION['winkelmand'][] = [
        'domein' => $domein,
        'prijs' => $prijs
    ];

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Domein Zoeker</title>
    <link rel="stylesheet" href="/domein-zoeker-minty-media/assets/css/style.css">
</head>
<body>

<nav>
    <span class="logo">Domein Zoeker</span>
    <a href="index.php">Zoeken</a>
    <a href="pages/winkelmand.php">Winkelmand (<?= isset($_SESSION['winkelmand']) ? count($_SESSION['winkelmand']) : 0 ?>)</a>
    <a href="pages/bestellingen.php">Bestellingen</a>
</nav>

<div class="container">
    <h1>Zoek een domein</h1>

    <!-- Zoekformulier -->
    <form class="zoek-form" method="POST" action="index.php">
        <input type="text" name="domein" placeholder="bijv. mijnwebsite" value="<?= htmlspecialchars($zoeknaam) ?>" required>
        <button type="submit">Zoeken</button>
    </form>

    <!-- Resultaten tabel -->
    <?php if (!empty($resultaten)): ?>
    <table>
        <thead>
            <tr>
                <th>Domein</th>
                <th>Status</th>
                <th>Prijs</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($resultaten as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['domain']) ?></td>
                <td>
                    <?php if ($r['status'] === 'free'): ?>
                        <span class="beschikbaar">Beschikbaar</span>
                    <?php else: ?>
                        <span class="niet-beschikbaar">Niet beschikbaar</span>
                    <?php endif; ?>
                </td>
                <td>€<?= number_format($r['price'], 2, ',', '.') ?>/jaar</td>
                <td>
                    <?php if ($r['status'] === 'free'): ?>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="toevoegen" value="1">
                            <input type="hidden" name="domein_naam" value="<?= htmlspecialchars($r['domain']) ?>">
                            <input type="hidden" name="domein_prijs" value="<?= $r['price'] ?>">
                            <button type="submit" class="btn-toevoegen">+ Toevoegen</button>
                        </form>
                    <?php else: ?>
                        <button class="btn-toevoegen" disabled>Niet beschikbaar</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['domein'])): ?>
        <p>Geen resultaten gevonden.</p>
    <?php endif; ?>
</div>

</body>
</html>