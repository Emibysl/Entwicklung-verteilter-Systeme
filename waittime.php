<?php
session_start();
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'process/process_waittime.php';
}

// waittime aus der Datei laden – Standard: "30 Minuten"
$waittime = file_exists('../wartezeit.txt') ? file_get_contents('../wartezeit.txt') : '30 Minuten';

// Versuche, Zahl und Einheit zu extrahieren
preg_match('/(\d+)\s(\w+)/', $waittime, $matches);
$waittimeNum = $matches[1] ?? 30;
$waittimeEinheit = $matches[2] ?? 'Minuten';

// Variablen für Header.php
$additionalCSS = '<link rel="stylesheet" href="assets/css/products.css">';
$pageTitle   = "Wartezeit-Dashboard";
$headerTitle = "Wartezeit-Dashboard";

include 'inc/header.php';
?>

<div class="edit-modal">
    <div class="edit-modal-header">
        <h2>Wartezeit bearbeiten</h2>
    </div>
    <div class="edit-modal-body">
        <!-- Aktuelle waittime anzeigen -->
        <p>Aktuelle Wartezeit: <strong><?= escape($waittime) ?></strong></p>

        <!-- Formular zur Aktualisierung der waittime -->
        <form action="waittime.php" method="POST">
            <label for="waittime_num">waittime:</label>
            <input type="number" id="waittime_num" name="waittime_num" value="<?= escape($waittimeNum) ?>" min="1" required>
            <select id="waittime_einheit" name="waittime_einheit">
                <option value="Minuten" <?= ($waittimeEinheit === 'Minuten') ? 'selected' : ''; ?>>Minuten</option>
                <option value="Stunden" <?= ($waittimeEinheit === 'Stunden') ? 'selected' : ''; ?>>Stunden</option>
            </select>
            <button type="submit">Wartezeit aktualisieren</button>
        </form>
    </div>
</div>

<!-- Menü-Popup -->
<div id="menu-popup" class="menu-popup">
    <div class="menu-popup-content">
        <!--hier wurde statt x &times; verwendet, weil es schöner aussieht-->
        <span class="close" onclick="toggleMenu()">&times;</span>
        <h2>Navigation</h2>
        <ul>
            <li><a href="mainDashboard.php">Speisekartenverwaltung</a></li>
            <li><a href="orders.php">Offene Bestellungen</a></li>
            <li><a href="products.php">Neues Produkt anlegen</a></li>
            <li><a href="waittime.php" id="active">Wartezeit anpassen</a></li>
            <li><a href="deliveryZones.php">Anpassung der Lieferbereiche (Kosten)</a></li>
        </ul>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>

</html>