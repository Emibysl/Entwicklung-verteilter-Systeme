<?php
session_start();
require_once 'inc/dbverb.php'; // DB Verbindung herstellen
require_once 'inc/functions.php'; // Funktionen einbinden

// Bei POST-Anfragen wird die process_product.php aufgerufen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'process/process_product.php';
}

//Prüfen, ob Suchbegriff vorhanden, sonst leer
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

//Produkte gruppiert nach Kategorie durch Funktion aus Functions.php
$productsByCategory = getProductsByCategory($conn, $searchTerm);
if (!$productsByCategory) {
    $noProducts = true;
}

$conn->close();

// Variablen für Header.php
$pageTitle   = "Restaurant-Dashboard";
$headerTitle = "Willkommen im Restaurant-Dashboard";

include 'inc/header.php';
?>

<main class="main">
    <div class="container">
        <h1 class="section__title">Speisekarte</h1>
        <!-- Suchbereich für Produkte -->
        <div class="search-container">
            <form class="search-form" method="GET">
                <!-- Eingabefeld für Produktsuche -->
                <input type="text" id="search-input" name="search" placeholder="Produkt suchen...">
            </form>
        </div>

        <!-- Produktliste -->
        <div class="products">
            <!-- Prüfen, ob Produkte gefunden sonst Rückmeldung, dass keine Produkte vorhanden -->
            <?php if ($noProducts): ?>
                <p>Keine Produkte vorhanden.</p>
            <?php else: ?>
                <!-- Jede Kategorie wird zum Akkordeon -->
                <?php foreach ($productsByCategory as $category => $products): ?>
                    <div class="accordion">
                        <!-- Akkordeon-Header zeigt Kategorienamen an und löst bei Klick die Funktion toggleAccordion() aus -->
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <?= escape($category) ?> <span>+</span>
                        </div>
                        <!-- Akkordeon enthält alle Produkte der jeweiligen Kategorie -->
                        <div class="accordion-content">
                            <div class="products">
                                <!-- Schleife über alle Produkte der aktuellen Kategorie -->
                                <?php foreach ($products as $product): ?>
                                    <div class="product" id="product_<?= escape($product['id']) ?>">
                                        <!-- Produktbild wird aus Uploads-Ordner geladen -->
                                        <img src="uploads/<?= escape($product['bild']) ?>" alt="<?= escape($product['name']) ?>">
                                        <div class="product-info">
                                            <!-- Produktname -->
                                            <h3><?= escape($product['name']) ?></h3>
                                            <!-- Falls Produktbeschreibung vorhanden, anzeigen -->
                                            <?php if (!empty($product['produktbeschreibung'])): ?>
                                                <p class="product-description"><?= escape($product['produktbeschreibung']) ?></p>
                                            <?php endif; ?>
                                            <!-- Falls Allergien vorhanden, anzeigen -->
                                            <?php if (!empty($product['allergien'])): ?>
                                                <p class="product-allergies">Allergien: <?= escape($product['allergien']) ?></p>
                                            <?php endif; ?>
                                            <!-- Produktpreis -->
                                            <p class="price">Preis: <?= escape($product['preis']) ?>€</p>
                                        </div>
                                        <div class="product-buttons">
                                            <button type="button" class="edit" onclick='openEditForm(<?= json_encode($product) ?>)'>Bearbeiten</button>
                                            <form method="POST" onsubmit="return confirmDelete();">
                                                <!-- Verstecktes Feld mit Produkt-ID, die gelöscht werden soll -->
                                                <input type="hidden" name="delete_id" value="<?= (int)$product['id'] ?>">
                                                <button id="löschbutton" type="submit">Löschen</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Menü-Popup -->
<div id="menu-popup" class="menu-popup">
    <div class="menu-popup-content">
        <!--hier wurde statt x &times; verwendet, weil es schöner aussieht-->
        <span class="close" onclick="toggleMenu()">&times;</span>
        <h2>Navigation</h2>
        <ul>
            <li><a href="mainDashboard.php" id="active">Speisekartenverwaltung</a></li>
            <li><a href="orders.php">Offene Bestellungen</a></li>
            <li><a href="products.php">Neues Produkt anlegen</a></li>
            <li><a href="waittime.php">Wartezeit anpassen</a></li>
            <li><a href="deliveryZones.php">Lieferbereiche anpassen</a></li>
        </ul>
    </div>
</div>

<!-- Bearbeitungs-Popup -->
<div id="editOverlay" class="edit-overlay" style="display: none;" onclick="closeEditForm()"></div>
<div class="edit-modal" id="editContainer" style="display: none;">
    <div class="edit-modal-header">
        <h2>Produkt bearbeiten</h2>
        <span class="edit-modal-close" onclick="closeEditForm()">&times;</span>
    </div>
    <div class="edit-modal-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_product_id">
            <label for="edit_name">Produktname:</label>
            <input type="text" id="edit_name" name="name" required>
            <label for="edit_kategorie">Kategorie:</label>
            <input type="text" id="edit_kategorie" name="kategorie" readonly required>
            <label for="edit_beschreibung">Produktbeschreibung:</label>
            <textarea id="edit_beschreibung" name="beschreibung"></textarea>
            <label>Allergien:</label><br>
            <div id="edit_allergien"></div>
            <label for="edit_preis">Preis (in Euro):</label>
            <input type="number" id="edit_preis" name="preis" step="0.01" required>
            <label for="edit_bild">Produktbild:</label>
            <input type="file" id="edit_bild" name="bild" accept="image/*">
            <div class="edit-modal-buttons">
                <button type="submit" class="save-button">Speichern</button>
                <button type="button" class="cancel-button" onclick="closeEditForm()">Abbrechen</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>

</html>