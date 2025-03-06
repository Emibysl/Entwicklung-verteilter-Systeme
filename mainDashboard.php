<?php
session_start();
require_once 'inc/dbverb.php';
require_once 'inc/functions.php';

//Bearbeiten oder Löschen von Produkten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'process/process_product.php';
}

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$productsByCategory = getProductsByCategory($conn, $searchTerm);
if (!$productsByCategory) {
    $noProducts = true;
}
$conn->close();

// Variablen für den Header
$pageTitle   = "Restaurant-Dashboard";
$headerTitle = "Willkommen im Restaurant-Dashboard";

include 'inc/header.php';
?>

<main class="main">
    <div class="container">
        <h1 class="section__title">Speisekarte</h1>
        <div class="search-container">
            <form class="search-form" method="GET" action="mainDashboard.php">
                <input type="text" id="search-input" name="search" placeholder="Produkt suchen..." autocomplete="off">
            </form>
        </div>

        <div class="products">
            <?php if (isset($noProducts) && $noProducts): ?>
                <p>Keine Produkte vorhanden.</p>
            <?php else: ?>
                <?php foreach ($productsByCategory as $category => $products): ?>
                    <div class="accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <?= escape($category) ?> <span>+</span>
                        </div>
                        <div class="accordion-content">
                            <div class="products">
                                <?php foreach ($products as $product): ?>
                                    <div class="product" id="product_<?= escape($product['id']) ?>">
                                        <img src="uploads/<?= escape($product['bild']) ?>" alt="<?= escape($product['name']) ?>">
                                        <div class="product-info">
                                            <h3><?= escape($product['name']) ?></h3>
                                            <?php if (!empty($product['produktbeschreibung'])): ?>
                                                <p class="product-description"><?= escape($product['produktbeschreibung']) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($product['allergien'])): ?>
                                                <p class="product-allergies">Allergien: <?= escape($product['allergien']) ?></p>
                                            <?php endif; ?>
                                            <p class="price">Preis: <?= escape($product['preis']) ?>€</p>
                                        </div>
                                        <div class="product-buttons">
                                            <button type="button" class="edit" onclick='openEditForm(<?= json_encode($product) ?>)'>Bearbeiten</button>
                                            <form method="POST" action="mainDashboard.php" onsubmit="return confirmDelete();">
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

<!-- Modale Fenster und Overlays -->
<!-- Menü-Popup -->
<div id="menu-popup" class="menu-popup">
    <div class="menu-popup-content">
        <span class="close" onclick="toggleMenu()">&times;</span>
        <h2>Navigation</h2>
        <ul>
            <li><a href="mainDashboard.php" style="font-weight: bold;">Speisekartenverwaltung✅</a></li>
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
        <form action="mainDashboard.php" method="POST" enctype="multipart/form-data">
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
                <button type="submit" class="save-btn">Speichern</button>
                <button type="button" class="cancel-btn" onclick="closeEditForm()">Abbrechen</button>
            </div>
        </form>
    </div>
</div>

<!-- Verstecktes Formular, das beim Klick auf "Löschen" abgesendet wird -->
<form id="deleteForm" action="mainDashboard.php" method="POST" style="display: none;">
    <input type="hidden" name="delete_id" id="delete_id">
</form>

<script src="assets/js/script.js"></script>
</body>

</html>