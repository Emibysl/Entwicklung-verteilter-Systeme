<?php
session_start();
include_once 'inc/dbverb.php'; // DB Verbindung herstellen
require_once 'inc/functions.php'; // Funktionen einbinden

// Heutige Bestellungen holen mit der FUnktion aus functions.php 
$result_all_orders = getTodayOrders($conn);

// Variablen für Header.php
$additionalCSS = '<link rel="stylesheet" href="assets/css/orders.css">';
$pageTitle   = "Bestellungen-Dashboard";
$headerTitle = "Bestellungen-Dashboard";

include 'inc/header.php';
?>

<main class="main">
    <div class="container">
        <br>
        <h1 class="section__title">Alle heutigen Bestellungen</h1>
        <table>
            <tr>
                <th>Bestell-ID</th>
                <th>Kundenname</th>
                <th>Adresse</th>
                <th>Telefonnummer</th>
                <th>Gesamtbetrag (inkl. Lieferkosten)</th>
                <th>Zahlungsmethode</th>
                <th>Anmerkungen</th>
                <th>Lieferinhalt</th>
                <th>Bestell-Typ</th>
                <th>Uhrzeit</th>
            </tr>
            <!-- Jede Bestellung -->
            <?php while ($row_all = $result_all_orders->fetch_assoc()): ?>
                <!-- Neue Zeile -->
                <tr>
                    <!-- Neue Zelle -->
                    <td><?= escape($row_all["BestellungID"]) ?></td>
                    <td><?= escape($row_all["KundeName"]) ?></td>
                    <td><?= escape($row_all["KundeAdresse"]) ?></td>
                    <td><?= escape($row_all["KundeTelefonnummer"]) ?></td>
                    <td>
                        <?= escape(number_format($row_all["Gesamtbetrag"], 2)) ?> €
                        <br>[🚗: <?= escape(number_format($row_all["Lieferkosten"], 2)) ?> €]
                    </td>
                    <td><?= escape($row_all["Zahlungsmethode"]) ?></td>
                    <td><?= escape($row_all["Anmerkungen"]) ?></td>
                    <td class="order-content"><?= $row_all["Lieferinhalt"] ?></td>
                    <td><?= escape($row_all["bestell_typ"]) ?></td>
                    <td><?= escape($row_all["Bestelldatum"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
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
            <li><a href="orders.php" id="active">Offene Bestellungen</a></li>
            <li><a href="products.php">Neues Produkt anlegen</a></li>
            <li><a href="waittime.php">Wartezeit anpassen</a></li>
            <li><a href="deliveryZones.php">Lieferbereiche anpassen</a></li>
        </ul>
    </div>
</div>
<script src="assets/js/script.js"></script>
</body>

</html>