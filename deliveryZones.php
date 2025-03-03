<?php
session_start();
require_once 'inc/dbverb.php';
require_once 'inc/functions.php';

// Bei POST-Anfragen wird die process_deliveryZones.php aufgerufen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'process/process_deliveryZones.php';
}

// Daten laden
$staedteResult = getCities($conn);
$zonenResult  = getZones($conn);
$conn->close();

// Variablen für den Header
$additionalCSS = '<link rel="stylesheet" href="assets/css/deliveryZones.css">';
$pageTitle   = "Lieferbereich-Dashboard";
$headerTitle = "Lieferbereich-Dashboard";

include 'inc/header.php';
?>

<main class="main">
    <div class="container">
        <!-- Städte bearbeiten -->
        <section>
            <h2>Städte bearbeiten</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Stadtname</th>
                        <th>Zone ID</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $staedteResult->fetch_assoc()): ?>
                        <tr>
                            <form method="POST" style="margin:0;">
                                <td>
                                    <input type="text" name="stadt_name" value="<?= escape($row['stadt_name']) ?>" style="width: 100%; padding: 5px;">
                                </td>
                                <td>
                                    <input type="number" name="zone_id" value="<?= escape($row['zone_id']) ?>" min="1" style="width: 80px;">
                                </td>
                                <td>
                                    <input type="hidden" name="stadt_id" value="<?= (int)$row['id'] ?>">
                                    <button type="submit" name="update_staedte" class="save-btn">Speichern</button>
                                    <button type="submit" name="delete_stadt" value="<?= (int)$row['id'] ?>" class="delete-btn" onclick="return confirm('Stadt wirklich löschen?');" id="löschbutton">Löschen</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Neue Stadt hinzufügen -->
            <form method="POST">
                <h3>Neue Stadt hinzufügen</h3>
                <input type="text" name="stadt_name" placeholder="Stadtname" required>
                <input type="number" name="zone_id" placeholder="Zone ID" min="1" required>
                <button type="submit" name="add_stadt">Hinzufügen</button>
            </form>
        </section>

        <!-- Zonen bearbeiten -->
        <section>
            <h2>Zonen bearbeiten</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mindestbestellwert</th>
                        <th>Lieferkosten</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $zonenResult->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><?= (int)$row['zonen_id'] ?></td>
                                <td>
                                    <input type="number" step="0.01" name="mindestbestellwert" value="<?= escape($row['mindestbestellwert']) ?>" min="0">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="lieferkosten" value="<?= escape($row['lieferkosten']) ?>" min="0">
                                </td>
                                <td>
                                    <input type="hidden" name="zone_id" value="<?= (int)$row['zonen_id'] ?>">
                                    <button type="submit" name="update_zonen" class="save-btn">Speichern</button>
                                    <button type="submit" name="delete_zone" value="<?= (int)$row['zonen_id'] ?>" class="delete-btn" id="löschbutton" onclick="return confirm('Zone wirklich löschen?');">Löschen</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Neue Zone hinzufügen -->
            <form method="POST">
                <h3>Neue Zone hinzufügen</h3>
                <!-- Es werden nur Mindestbestellwert und Lieferkosten abgefragt; die neue zonen_id wird automatisch berechnet -->
                <input type="number" step="0.01" name="mindestbestellwert" placeholder="Mindestbestellwert" min="0" required>
                <input type="number" step="0.01" name="lieferkosten" placeholder="Lieferkosten" min="0" required>
                <button type="submit" name="add_zone">Hinzufügen</button>
            </form>
        </section>
    </div>
</main>

<!-- Menü-Popup -->
<div id="menu-popup" class="menu-popup">
    <div class="menu-popup-content">
        <span class="close" onclick="toggleMenu()">&times;</span>
        <h2>Navigation</h2>
        <ul>
            <li><a href="mainDashboard.php">Speisekartenverwaltung</a></li>
            <li><a href="orders.php">Offene Bestellungen</a></li>
            <li><a href="products.php">Neues Produkt anlegen</a></li>
            <li><a href="waittime.php">Wartezeit anpassen</a></li>
            <li><a href="deliveryZones.php" style="font-weight: bold;">Lieferbereiche anpassen✅</a></li>
        </ul>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>

</html>