<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <!-- Damit die Seite auf mobilen Geräten responsiv dargestellt wird (YOUTUBE-VIDEO)-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Seitentitel dynamisch oder Dashboard-->
    <title><?= $pageTitle ?? 'Dashboard' ?></title>
    <link rel="stylesheet" href="assets/css/mainDashboard.css">
    <!--eventuell zusätzliche CSS-Dateien neben mainDashboard.css-->
    <?php if (isset($additionalCSS)) echo $additionalCSS; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <!--Überschrift dynamisch oder Dashboard-->
            <h1><?= $headerTitle ?? 'Dashboard' ?></h1>
            <!--Menü – beim Klicken wird die JS-Funktion toggleMenu() aufgerufen, die Navigationsmenü ein- oder ausblendet-->
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
        </div>
    </div>
    <!--Überprüfung, ob Erfolgsmeldung/Fehlermeldung in der Session gesetzt ist-->
    <?php if (isset($_SESSION['success_message'])): ?>
        <!--Unterscheidung, ob Meldung Fehler ist oder nicht-->
        <?php if (isset($_SESSION['message_type']) && $_SESSION['message_type'] === 'error'): ?>
            <div class="error-message">
                <!--Meldung anzeigen-->
                <?= escape($_SESSION['success_message']) ?>
            </div>
        <?php else: ?>
            <div class="success-message">
                <!--Meldung anzeigen-->
                <?= escape($_SESSION['success_message']) ?>
            </div>
        <?php endif; ?>
        <?php
        // Meldung aus Session löschen
        unset($_SESSION['success_message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>