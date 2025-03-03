<?php /*
session_start();
date_default_timezone_set('Europe/Berlin');

include_once 'inc/dbverb.php';
include_once 'inc/functions.php';

$selectedDate = isset($_GET['selected_date']) ? $_GET['selected_date'] : date("Y-m-d");
$currentDateTime = date("d.m.Y", strtotime($selectedDate)) . ' ' . date("H:i", time());

// Erzeuge den Monatsstring (YYYY-MM) und formatiere den Monatsnamen in Deutsch
$selectedMonth = substr($selectedDate, 0, 7);
$currentMonthText = strftime("%B %Y", strtotime($selectedMonth . "-01"));

$startOfMonth = $selectedMonth . "-01";
$endOfMonth = date("Y-m-t", strtotime($startOfMonth));

// Abfragen über Funktionen
$dataTotal = getMonthlyTotals($conn, $startOfMonth, $endOfMonth);
$totalAmount = $dataTotal['gesamtbetrag'] ?? 0;
$totalOrders = $dataTotal['anzahl'] ?? 0;

$orderTypes = getOrderTypesTotals($conn, $startOfMonth, $endOfMonth);
$abholenTotal = isset($orderTypes['Abholen']) ? $orderTypes['Abholen']['gesamtbetrag'] : 0;
$abholenCount = isset($orderTypes['Abholen']) ? $orderTypes['Abholen']['anzahl'] : 0;
$lieferungTotal = isset($orderTypes['Liefern']) ? $orderTypes['Liefern']['gesamtbetrag'] : 0;
$lieferungCount = isset($orderTypes['Liefern']) ? $orderTypes['Liefern']['anzahl'] : 0;

$lieferkostenTotal = getDeliveryCosts($conn, $startOfMonth, $endOfMonth);

$totalVorLiefern = $abholenTotal + ($lieferungTotal - $lieferkostenTotal);
$totalUebersicht = $totalVorLiefern + $lieferkostenTotal;

$paymentMethods = getPaymentMethods($conn, $startOfMonth, $endOfMonth);

$taxRate7 = 7;
$barTotal = isset($paymentMethods['Bar']['amount']) ? $paymentMethods['Bar']['amount'] : 0;
$ecTotal = isset($paymentMethods['Karte']['amount']) ? $paymentMethods['Karte']['amount'] : 0;

$tax7AmountBar = $barTotal / (1 + $taxRate7 / 100) * ($taxRate7 / 100);
$barNetto = $barTotal - $tax7AmountBar;

$tax7AmountEC = $ecTotal / (1 + $taxRate7 / 100) * ($taxRate7 / 100);
$ecNetto = $ecTotal - $tax7AmountEC;

$totalNetto = $barTotal + $ecTotal - ($tax7AmountBar + $tax7AmountEC);
$totalBrutto = $barTotal + $ecTotal;

$conn->close();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monatsabschluss</title>
    <link rel="stylesheet" href="assets/css/Monatsabschluss.css">
</head>
<body>
    <!-- Back Button -->
    <a href="kennzahlen.php" class="back-button">Zurück zu den Kennzahlen</a>
    <div class="header">
        <h2>Foody</h2>
        <p>Marienplatz 2</p>
        <p>88212</p>
        <p>Tel: 0751/189992700</p>
        <p><strong>Monat: <?= escape($currentMonthText); ?></strong></p>
        <hr>
    </div>

    <!-- Übersicht -->
    <p><strong>Übersicht</strong></p>
    <table class="totals-table">
        <tr>
            <td>Abholen</td>
            <td><?= escape("$abholenCount x"); ?></td>
            <td>EUR <?= escape(number_format($abholenTotal, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>Lieferung</td>
            <td><?= escape("$lieferungCount x"); ?></td>
            <td>EUR <?= escape(number_format(($lieferungTotal - $lieferkostenTotal), 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>Total ohne Lieferkosten</td>
            <td></td>
            <td>EUR <?= escape(number_format($totalVorLiefern, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>zzgl. Fahrtkosten</td>
            <td></td>
            <td>EUR <?= escape(number_format($lieferkostenTotal, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td></td>
            <td><strong>EUR <?= escape(number_format($totalUebersicht, 2, ',', '.')); ?></strong></td>
        </tr>
    </table>
    <hr>

    <!-- Zahlungsarten -->
    <p><strong>Zahlungsarten</strong></p>
    <table class="payment-table">
        <tr>
            <td>Bar</td>
            <td><?= escape($paymentMethods['Bar']['count'] ?? 0); ?></td>
            <td>EUR <?= escape(number_format($barTotal, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>EC Karte (Terminal)</td>
            <td><?= escape($paymentMethods['Karte']['count'] ?? 0); ?></td>
            <td>EUR <?= escape(number_format($ecTotal, 2, ',', '.')); ?></td>
        </tr>
    </table>
    <hr>

    <!-- Steueraufteilung: Bareinnahmen -->
    <p><strong>Bareinnahmen</strong></p>
    <table class="tax-table">
        <tr>
            <td>Netto</td>
            <td>EUR <?= escape(number_format($barNetto, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>7% MwSt</td>
            <td>EUR <?= escape(number_format($tax7AmountBar, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>Brutto</td>
            <td>EUR <?= escape(number_format($barTotal, 2, ',', '.')); ?></td>
        </tr>
    </table>
    <hr>

    <!-- Steueraufteilung: Kreditzahlungen -->
    <p><strong>Kreditzahlungen</strong></p>
    <table class="tax-table">
        <tr>
            <td>Netto</td>
            <td>EUR <?= escape(number_format($ecNetto, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>7% MwSt</td>
            <td>EUR <?= escape(number_format($tax7AmountEC, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>Brutto</td>
            <td>EUR <?= escape(number_format($ecTotal, 2, ',', '.')); ?></td>
        </tr>
    </table>
    <hr>

    <!-- Summen -->
    <p><strong>Summen</strong></p>
    <table class="totals-table">
        <tr>
            <td>Netto</td>
            <td>EUR <?= escape(number_format($totalNetto, 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td>Gesamt MwSt</td>
            <td>EUR <?= escape(number_format(($tax7AmountBar + $tax7AmountEC), 2, ',', '.')); ?></td>
        </tr>
        <tr>
            <td><strong>Brutto</strong></td>
            <td><strong>EUR <?= escape(number_format($totalBrutto, 2, ',', '.')); ?></strong></td>
        </tr>
    </table>

    <div class="footer">
        <p>Erzeugt am <?= escape($currentDateTime); ?></p>
    </div>
</body>
</html>
*/