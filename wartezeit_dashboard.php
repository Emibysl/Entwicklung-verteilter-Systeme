<?php
session_start();
require_once 'inc/functions.php';

// Wartezeit aus der Datei laden – Standard: "30 Minuten"
$wartezeit = file_exists('wartezeit.txt') ? file_get_contents('wartezeit.txt') : '30 Minuten';

// Versuche, Zahl und Einheit zu extrahieren
preg_match('/(\d+)\s(\w+)/', $wartezeit, $matches);
$wartezeitNum = $matches[1] ?? 30;
$wartezeitEinheit = $matches[2] ?? 'Minuten';
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Wartezeit Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/mainDashboard.css">
    <link rel="stylesheet" href="assets/css/produkte.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- HEADER -->
    <div class="header">
        <div class="header-content">
            <h1>Wartezeitverwaltung</h1>
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
        </div>
    </div>

    <!-- Erfolgsmeldung, falls vorhanden -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message">
            <?= escape($_SESSION['success_message']) ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <div class="edit-modal">
        <div class="edit-modal-header">
            <h2>Wartezeit bearbeiten</h2>
        </div>
        <div class="edit-modal-body">
            <!-- Aktuelle Wartezeit anzeigen -->
            <p>Aktuelle Wartezeit: <strong><?= escape($wartezeit) ?></strong></p>

            <!-- Formular zur Aktualisierung der Wartezeit -->
            <form method="POST" action="process/process_waittime.php">
                <label for="wartezeit_num">Wartezeit:</label>
                <input type="number" id="wartezeit_num" name="wartezeit_num" value="<?= escape($wartezeitNum) ?>" min="1" required>
                <select id="wartezeit_einheit" name="wartezeit_einheit">
                    <option value="Minuten" <?= ($wartezeitEinheit === 'Minuten') ? 'selected' : ''; ?>>Minuten</option>
                    <option value="Stunden" <?= ($wartezeitEinheit === 'Stunden') ? 'selected' : ''; ?>>Stunden</option>
                </select>
                <button type="submit">Wartezeit aktualisieren</button>
            </form>
        </div>
    </div>

    <!-- Menü-Popup -->
    <div id="menu-popup" class="menu-popup">
        <div class="menu-popup-content">
            <span class="close" onclick="toggleMenu()">&times;</span>
            <h2>Navigation</h2>
            <ul>
                <li><a href="mainDashboard.php">Speisekartenverwaltung</a></li>
                <li><a href="orders.php">Offene Bestellungen</a></li>
                <li><a href="products.php">Neues Produkt anlegen</a></li>
                <!--<li><a href="kennzahlen.php">Kennzahlenbereich</a></li>-->
                <li><a href="wartezeit_dashboard.php" style="font-weight: bold;">Wartezeit anpassen✅</a></li>
                <li><a href="deliveryZones.php">Anpassung der Lieferbereiche (Kosten)</a></li>
            </ul>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>

</html>