<?php /*
session_start();
date_default_timezone_set('Europe/Berlin');
require_once 'inc/dbverb.php';
require_once 'inc/functions.php';

$selectedDate = isset($_GET['selected_date']) ? $_GET['selected_date'] : date("Y-m-d");
$currentDateTime = date("d.m.Y", strtotime($selectedDate)) . ' ' . date("H:i", time());

// Übersichtsdaten abrufen
$overview = getDailyOverview($conn, $selectedDate);
$paymentOverview = getPaymentOverview($conn, $selectedDate, 7);

// Einzelne Variablen extrahieren
$abholenTotal = $overview['abholenTotal'];
$abholenCount = $overview['abholenCount'];
$lieferungTotal = $overview['lieferungTotal'];
$lieferungCount = $overview['lieferungCount'];
$lieferkostenTotal = $overview['lieferkostenTotal'];
$totalVorLiefern = $overview['totalVorLiefern'];
$totalUebersicht = $overview['totalUebersicht'];

$paymentMethods = $paymentOverview['paymentMethods'];
$barTotal = $paymentOverview['barTotal'];
$ecTotal = $paymentOverview['ecTotal'];
$barNetto = $paymentOverview['barNetto'];
$tax7AmountBar = $paymentOverview['tax7AmountBar'];
$ecNetto = $paymentOverview['ecNetto'];
$tax7AmountEC = $paymentOverview['tax7AmountEC'];
$totalNetto = $paymentOverview['totalNetto'];
$totalBrutto = $paymentOverview['totalBrutto'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagesabschluss</title>
    <link rel="stylesheet" href="assets/css/Tagesabschluss.css">
</head>
<body>
    <!-- Back Button -->
    <a href="kennzahlen.php" class="back-button">Zurück zu den Kennzahlen</a>
    <div class="header">
        <h2>Foody</h2>
        <p>Marienplatz 2</p>
        <p>88212 Ravensburg</p>
        <p>Tel: 0751/189992700</p>
        <p>Datum: <?= escape($currentDateTime); ?></p>
        <hr>
    </div>
    <!-- Übersicht -->
    <p><strong>Übersicht</strong></p>
    <table class="totals-table">
        <tr>
            <td>Abholen</td>
            <td><?= escape($abholenCount) . " x"; ?></td>
            <td>EUR <?= number_format($abholenTotal, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>Lieferung</td>
            <td><?= escape($lieferungCount) . " x"; ?></td>
            <td>EUR <?= number_format(($lieferungTotal - $lieferkostenTotal), 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>Total ohne Lieferkosten</td>
            <td></td>
            <td>EUR <?= number_format($totalVorLiefern, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>zzgl. Fahrtkosten</td>
            <td></td>
            <td>EUR <?= number_format($lieferkostenTotal, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td></td>
            <td><strong>EUR <?= number_format($totalUebersicht, 2, ',', '.'); ?></strong></td>
        </tr>
    </table>
    <hr>
    <!-- Zahlungsarten -->
    <p><strong>Zahlungsarten</strong></p>
    <table class="payment-table">
        <tr>
            <td>Bar</td>
            <td><?= escape($paymentMethods['Bar']['count'] ?? 0); ?></td>
            <td>EUR <?= number_format($barTotal, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>EC Karte (Terminal)</td>
            <td><?= escape($paymentMethods['Karte']['count'] ?? 0); ?></td>
            <td>EUR <?= number_format($ecTotal, 2, ',', '.'); ?></td>
        </tr>
    </table>
    <hr>
    <hr>
    <!-- Steueraufteilung -->
    <p><strong>Bareinnahmen</strong></p>
    <table class="tax-table">
        <tr>
            <td>Netto</td>
            <td>EUR <?= number_format($barNetto, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>7% MwSt</td>
            <td>EUR <?= number_format($tax7AmountBar, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>Brutto</td>
            <td>EUR <?= number_format($barTotal, 2, ',', '.'); ?></td>
        </tr>
    </table>
    <hr>
    <p><strong>Kreditzahlungen</strong></p>
    <table class="tax-table">
        <tr>
            <td>Netto</td>
            <td>EUR <?= number_format($ecNetto, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>7% MwSt</td>
            <td>EUR <?= number_format($tax7AmountEC, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>Brutto</td>
            <td>EUR <?= number_format($ecTotal, 2, ',', '.'); ?></td>
        </tr>
    </table>
    <hr>
    <!-- Summen -->
    <p><strong>Summen</strong></p>
    <table class="totals-table">
        <tr>
            <td>Netto</td>
            <td>EUR <?= number_format($totalNetto, 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>Gesamt MwSt</td>
            <td>EUR <?= number_format(($tax7AmountBar + $tax7AmountEC), 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td><strong>Brutto</strong></td>
            <td><strong>EUR <?= number_format($totalBrutto, 2, ',', '.'); ?></strong></td>
        </tr>
    </table>
    <div class="footer">
        <p>Erzeugt am <?= escape($currentDateTime); ?></p>
    </div>
</body>
</html>
*/