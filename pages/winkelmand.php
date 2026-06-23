<?php
session_start();

// Domein verwijderen uit winkelmand
if (isset($_POST['verwijderen'])) {
    $index = $_POST['index'];

    // Verwijder het item op die positie
    if (isset($_SESSION['winkelmand'][$index])) {
        array_splice($_SESSION['winkelmand'], $index, 1);
    }

    header('Location: winkelmand.php');
    exit;
}

$winkelmand = $_SESSION['winkelmand'] ?? [];

// Subtotaal berekenen
$subtotaal = 0;
foreach ($winkelmand as $item) {
    $subtotaal += $item['prijs'];
}

// 21% BTW berekenen
$btw = $subtotaal * 0.21;
$totaal = $subtotaal + $btw;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelmand</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav>
    <span>Domein Zoeker</span>
    <a href="../index.php">Zoeken</a>
    <a href="winkelmand.php">Winkelmand (<?= count($winkelmand) ?>)</a>
    <a href="bestellingen.php">Bestellingen</a>
</nav>

<div class="container">
    <h1>Winkelmand</h1>

    <?php if (empty($winkelmand)): ?>
        <p>Je winkelmand is leeg. <a href="../index.php">Zoek een domein</a>.</p>
    <?php else: ?>

    <table>
        <thead>
            <tr>
                <th>Domein</th>
                <th>Prijs/jaar</th>
                <th>Verwijderen</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($winkelmand as $i => $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['domein']) ?></td>
                <td>€<?= number_format($item['prijs'], 2, ',', '.') ?></td>
                <td>
                    <form method="POST" action="winkelmand.php">
                        <input type="hidden" name="verwijderen" value="1">
                        <input type="hidden" name="index" value="<?= $i ?>">
                        <button type="submit" class="btn-verwijderen">Verwijder</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Prijsoverzicht -->
    <div class="winkelmand-info">
        <p>Subtotaal: <strong>€<?= number_format($subtotaal, 2, ',', '.') ?></strong></p>
        <p>BTW (21%): <strong>€<?= number_format($btw, 2, ',', '.') ?></strong></p>
        <p>Totaal: <strong>€<?= number_format($totaal, 2, ',', '.') ?></strong></p>
    </div>

    <br>
    <a href="checkout.php"><button class="btn-bestel">Naar checkout →</button></a>

    <?php endif; ?>
</div>

</body>
</html>