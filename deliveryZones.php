<?php
session_start();
require_once 'inc/dbverb.php'; // DB Verbindung herstellen
require_once 'inc/functions.php'; // Funktionen einbinden

// Bei POST-Anfragen wird process_deliveryZones.php aufgerufen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'process/process_deliveryZones.php';
}

// Daten laden mit get-Funktionen aus functions.php
$citiesResult = getCities($conn);
$zonesResult  = getZones($conn);

$conn->close();

// Variablen für Header.php
$additionalCSS = '<link rel="stylesheet" href="assets/css/deliveryZones.css">';
$pageTitle   = "Lieferbereich-Dashboard";
$headerTitle = "Lieferbereich-Dashboard";

include 'inc/header.php';
?>

<main class="main">
    <div class="container">
        <section>
            <h2>Städte bearbeiten</h2>
            <!-- Tabelle fürs Städte bearbeiten -->
            <table>
                <thead>
                    <tr>
                        <th>Stadtname</th>
                        <th>Zone ID</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Schleife über alle Städte aus DB -->
                    <?php while ($row = $citiesResult->fetch_assoc()): ?>
                        <tr>
                            <form method="POST" style="margin:0;">
                                <td>
                                    <!-- Eingabefeld für Stadtnamen, Platzhalter ist aktueller Name -->
                                    <input type="text" name="stadt_name" value="<?= escape($row['stadt_name']) ?>">
                                </td>
                                <td>
                                    <!-- Eingabefeld für Zonenid-->
                                    <input type="number" name="zone_id" value="<?= escape($row['zone_id']) ?>">
                                </td>
                                <td>
                                    <!-- Verstecktes Feld mit eindeutiger Stadt-ID -->
                                    <input type="hidden" name="stadt_id" value="<?= (int)$row['id'] ?>">
                                    <button type="submit" name="update_staedte" class="save-button">Speichern</button>
                                    <button type="submit" name="delete_stadt" value="<?= (int)$row['id'] ?>" class="delete-button" onclick="return confirm('Stadt wirklich löschen?');" id="löschbutton">Löschen</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Formular zum Hinzufügen einer neuen Stadt -->
            <form method="POST">
                <h3>Neue Stadt hinzufügen</h3>
                <input type="text" name="stadt_name" placeholder="Stadtname" required>
                <input type="number" name="zone_id" placeholder="Zone ID" min="1" required>
                <button type="submit" name="add_stadt">Hinzufügen</button>
            </form>
        </section>

        <section>
            <h2>Zonen bearbeiten</h2>
            <!-- Tabelle fürs Zone bearbeiten -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mindestbestellwert</th>
                        <th>Lieferkosten</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Schleife über alle Zonen aus DB -->
                    <?php while ($row = $zonesResult->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><?= (int)$row['zonen_id'] ?></td>
                                <td>
                                    <!-- Eingabefeld für Mindestbestellwert -->
                                    <input type="number" step="0.01" name="mindestbestellwert" value="<?= escape($row['mindestbestellwert']) ?>" min="0">
                                </td>
                                <td>
                                    <!-- Eingabefeld für Lieferkosten -->
                                    <input type="number" step="0.01" name="lieferkosten" value="<?= escape($row['lieferkosten']) ?>" min="0">
                                </td>
                                <td>
                                    <!-- Verstecktes Feld mit eindeutiger Zonenid -->
                                    <input type="hidden" name="zone_id" value="<?= (int)$row['zonen_id'] ?>">
                                    <button type="submit" name="update_zonen" class="save-button">Speichern</button>
                                    <button type="submit" name="delete_zone" value="<?= (int)$row['zonen_id'] ?>" class="delete-button" id="löschbutton" onclick="return confirm('Zone wirklich löschen?');">Löschen</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Formular zum Hinzufügen einer neuen Zone -->
            <form method="POST">
                <h3>Neue Zone hinzufügen</h3>
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
        <!--hier wurde statt x &times; verwendet, weil es schöner aussieht-->
        <span class="close" onclick="toggleMenu()">&times;</span>
        <h2>Navigation</h2>
        <ul>
            <li><a href="mainDashboard.php">Speisekartenverwaltung</a></li>
            <li><a href="orders.php">Offene Bestellungen</a></li>
            <li><a href="products.php">Neues Produkt anlegen</a></li>
            <li><a href="waittime.php">Wartezeit anpassen</a></li>
            <li><a href="deliveryZones.php" id="active">Lieferbereiche anpassen</a></li>
        </ul>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>

</html>