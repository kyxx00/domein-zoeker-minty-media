<?php
session_start();
require_once '../includes/db.php';

// Alle bestellingen ophalen, nieuwste eerst
$stmt = $pdo->query("SELECT * FROM bestellingen ORDER BY aangemaakt_op DESC");
$bestellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestellingen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav>
    <span class="logo">Domein Zoeker</span>
    <a href="../index.php">Zoeken</a>
    <a href="winkelmand.php">Winkelmand (<?= isset($_SESSION['winkelmand']) ? count($_SESSION['winkelmand']) : 0 ?>)</a>
    <a href="bestellingen.php">Bestellingen</a>
</nav>

<div class="container">
    <h1>Bestellingen</h1>

    <!-- Succesmelding na plaatsen bestelling -->
    <?php if (isset($_GET['succes'])): ?>
        <div class="melding succes">✔ Bestelling succesvol geplaatst!</div>
    <?php endif; ?>

    <?php if (empty($bestellingen)): ?>
        <p>Er zijn nog geen bestellingen.</p>
    <?php else: ?>

    <?php foreach ($bestellingen as $bestelling): ?>
        <div style="background:white; border:1px solid #ddd; padding:15px; margin-bottom:20px; border-radius:4px;">
            <p><strong>Bestelling #<?= $bestelling['id'] ?></strong> — <?= htmlspecialchars($bestelling['naam']) ?> (<?= htmlspecialchars($bestelling['email']) ?>)</p>
            <p style="font-size:13px; color:#777;"><?= $bestelling['aangemaakt_op'] ?></p>

            <!-- Domeinen van deze bestelling ophalen -->
            <?php
            $stmt2 = $pdo->prepare("SELECT * FROM bestelling_domeinen WHERE bestelling_id = ?");
            $stmt2->execute([$bestelling['id']]);
            $domeinen = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <table style="margin-top:10px;">
                <thead>
                    <tr>
                        <th>Domein</th>
                        <th>Prijs</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($domeinen as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['domein']) ?></td>
                        <td>€<?= number_format($d['prijs'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Prijsoverzicht per bestelling -->
            <div style="margin-top:10px; font-size:14px;">
                <p>Subtotaal: €<?= number_format($bestelling['subtotaal'], 2, ',', '.') ?></p>
                <p>BTW (21%): €<?= number_format($bestelling['btw'], 2, ',', '.') ?></p>
                <p><strong>Totaal: €<?= number_format($bestelling['totaal'], 2, ',', '.') ?></strong></p>
            </div>
        </div>
    <?php endforeach; ?>

    <?php endif; ?>
</div>

</body>
</html>