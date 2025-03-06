<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?></title>
    <link rel="stylesheet" href="assets/css/mainDashboard.css">
    <?php if (isset($additionalCSS)) echo $additionalCSS; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <h1><?= $headerTitle ?? 'Dashboard' ?></h1>
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
        </div>
    </div>
    <?php if (isset($_SESSION['success_message'])): ?>
    <?php if (isset($_SESSION['message_type']) && $_SESSION['message_type'] === 'error'): ?>
        <div class="error-message">
            <?= escape($_SESSION['success_message']) ?>
        </div>
    <?php else: ?>
        <div class="success-message">
            <?= escape($_SESSION['success_message']) ?>
        </div>
    <?php endif; ?>
    <?php 
        unset($_SESSION['success_message']); 
        unset($_SESSION['message_type']);
    ?>
<?php endif; ?>
