<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Datenbankverbindung herstellen
include_once '../inc/dbverb.php';

// Überprüfen, ob ein Datum ausgewählt wurde, oder auf das heutige Datum zurückgreifen
//$selectedDate = isset($_GET['selected_date']) ? $_GET['selected_date'] : date("Y-m-d");
//$currentDateTime = date("d.m.Y H:i", strtotime($selectedDate));

$selectedDate = isset($_GET['selected_date']) ? $_GET['selected_date'] : date("Y-m-d");
$currentDateTime = date("d.m.Y", strtotime($selectedDate)) . ' ' . date("H:i");



// Tagesdaten abrufen: Gesamtbetrag und Anzahl Bestellungen
$sqlTotal = "SELECT SUM(Gesamtbetrag) AS gesamtbetrag, SUM(drinkssumme) AS drinkssumme, COUNT(*) AS anzahl FROM Bestellungentest WHERE DATE(Bestelldatum) = ?";
$stmtTotal = $conn->prepare($sqlTotal);
$stmtTotal->bind_param("s", $selectedDate);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$dataTotal = $resultTotal->fetch_assoc();
$stmtTotal->close();

$totalAmount = $dataTotal['gesamtbetrag'] ?? 0;
$totalDrinkSum = $dataTotal['drinkssumme'] ?? 0;  // Speichert die drinkssumme des Tages
$totalOrders = $dataTotal['anzahl'] ?? 0;


// Summe Abholen und Lieferung berechnen
$sqlOrderTypes = "SELECT bestell_typ, SUM(Gesamtbetrag) AS gesamtbetrag, SUM(drinkssumme) AS drinkssumme, COUNT(*) AS anzahl FROM Bestellungentest WHERE DATE(Bestelldatum) = ? GROUP BY bestell_typ";
$stmtOrderTypes = $conn->prepare($sqlOrderTypes);
$stmtOrderTypes->bind_param("s", $selectedDate);
$stmtOrderTypes->execute();
$resultOrderTypes = $stmtOrderTypes->get_result();

$abholenTotal = $lieferungTotal = 0;
$abholenCount = $lieferungCount = 0;
$abholenDrinktotal = $lieferungDrinktotal = 0;
while ($row = $resultOrderTypes->fetch_assoc()) {
    if ($row['bestell_typ'] === 'Abholen') {
        $abholenTotal = $row['gesamtbetrag'];
        $abholenCount = $row['anzahl'];
		$abholenDrinktotal = $row['drinkssumme'];
    } elseif ($row['bestell_typ'] === 'Liefern') {
        $lieferungTotal = $row['gesamtbetrag'];
        $lieferungCount = $row['anzahl'];
		$lieferungDrinktotal = $row['drinkssumme'];
    }
}
$stmtOrderTypes->close();

// Stornos berechnen (Freigeben = 4)
$sqlStornos = "SELECT COUNT(*) AS anzahl, SUM(Gesamtbetrag) AS gesamtbetrag, SUM(drinkssumme) AS drinkssumme FROM Bestellungentest WHERE DATE(Bestelldatum) = ? AND Freigegeben = 4";
$stmtStornos = $conn->prepare($sqlStornos);
$stmtStornos->bind_param("s", $selectedDate);
$stmtStornos->execute();
$resultStornos = $stmtStornos->get_result();
$dataStornos = $resultStornos->fetch_assoc();
$stmtStornos->close();

$stornoCount = $dataStornos['anzahl'] ?? 0;
$stornoTotal = $dataStornos['gesamtbetrag'] ?? 0;
$stornoDrinks = $dataStornos['drinkssumme'] ?? 0;


// Lieferkosten berechnen
$sqlDeliveryCosts = "SELECT SUM(Lieferkosten) AS lieferkosten FROM Bestellungentest WHERE DATE(Bestelldatum) = ? AND bestell_typ = 'Liefern'";
$stmtDeliveryCosts = $conn->prepare($sqlDeliveryCosts);
$stmtDeliveryCosts->bind_param("s", $selectedDate);
$stmtDeliveryCosts->execute();
$resultDeliveryCosts = $stmtDeliveryCosts->get_result();
$dataDeliveryCosts = $resultDeliveryCosts->fetch_assoc();
$stmtDeliveryCosts->close();

$lieferkostenTotal = $dataDeliveryCosts['lieferkosten'] ?? 0;

//TOTAL VOR LIEFERKOSTEN
$totalVorLiefern = $abholenTotal + ($lieferungTotal - $lieferkostenTotal);
// Gesamtbetrag der Übersicht (Abholen + Lieferung - Stornos)
$totalUebersicht = ($totalVorLiefern + $lieferkostenTotal) - $stornoTotal; //ACHTUNG CAN: WENN DIE STORNIERTEN BESTELLUNGEN NOCH IN DER DB BLEIBEN MIT FREIGEGEBEN=4, DANN SOLLTEN WIR DAS "- STORNOTOTAL" DRINLASSEN

// Zahlungsarten
$sqlPayments = "SELECT Zahlungsmethode, SUM(Gesamtbetrag) AS gesamtbetrag , SUM(drinkssumme) AS drinkssumme, COUNT(*) AS anzahl FROM Bestellungentest WHERE DATE(Bestelldatum) = ? AND Freigegeben !=4 GROUP BY Zahlungsmethode";
$stmtPayments = $conn->prepare($sqlPayments);
$stmtPayments->bind_param("s", $selectedDate);
$stmtPayments->execute();
$resultPayments = $stmtPayments->get_result();
$paymentMethods = [];
while ($row = $resultPayments->fetch_assoc()) {
    $paymentMethods[$row['Zahlungsmethode']] = [
        'amount' => $row['gesamtbetrag'],
		'amountDrink' => $row['drinkssumme'],
        'count' => $row['anzahl']
    ];
}
$stmtPayments->close();

// Steuerberechnung für Bar- und Kartenzahlungen (7% und 19%)
$taxRate7 = 7;
$taxRate19 = 19;

// Beispielwerte für Kredit- und Rechnungszahlungen, die noch nicht implementiert sind
$creditTotal = 0; // Beispielwert
$invoiceTotal = 0; // Beispielwert

// Steuerbeträge
$barTotal = $paymentMethods['Bar']['amount'] ?? 0;
$barTotalDrink = $paymentMethods['Bar']['amountDrink'] ?? 0;
$ecTotal = $paymentMethods['Karte']['amount'] ?? 0;
$ecTotalDrink = $paymentMethods['Karte']['amountDrink'] ?? 0;

$tax7AmountBar = $barTotal / (1 + $taxRate7 / 100) * ($taxRate7 / 100);
$tax19AmountBar = $barTotalDrink / (1 + $taxRate19 / 100) * ($taxRate19 / 100); //VORÜBERGEHEND

$barNetto = $barTotal - $tax7AmountBar -$tax19AmountBar;

$tax7AmountEC = $ecTotal / (1 + $taxRate7 / 100) * ($taxRate7 / 100);
$tax19AmountEC = $ecTotalDrink / (1 + $taxRate19 / 100) * ($taxRate19 / 100); //VORÜBERGEHEND

$ecNetto = $ecTotal - $tax7AmountEC -$tax19AmountEC;

$totalNetto = $barTotal + $ecTotal - ($tax7AmountBar + $tax19AmountBar + $tax7AmountEC + $tax19AmountEC);
$totalBrutto = $barTotal + $ecTotal;

$abholenCount = $abholenCount ?? 0;
$abholenTotal = $abholenTotal ?? 0.00;
$lieferungCount = $lieferungCount ?? 0;
$lieferungTotal = $lieferungTotal ?? 0.00;


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagesabschluss</title>
    <style>
        body {
            font-family: Courier, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            max-width: 280px;
            margin: auto;
            padding: 20px;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
		
		/* Button Styling */
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 5px 10px;
            font-size: 12px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
		
		/* Spezielle CSS-Regel für den Druck */
		@media print {
			.back-button {
				display: none;
			}
		}

        .header, .totals {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2, .footer h4 {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
        }

        /* Tabellenformatierung */
        .totals-table, .payment-table, .tax-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .totals-table td, .payment-table td, .tax-table td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
			white-space: nowrap;

        }

        .totals-table td:first-child, .payment-table td:first-child, .tax-table td:first-child {
            text-align: left;
        }

        /* Rechtsbündigkeit für die letzte Spalte */
        .totals-table td:last-child, .payment-table td:last-child, .tax-table td:last-child {
            text-align: right;
        }

        .payment-table td:nth-child(2) {
            text-align: center; /* Zentrierung für den Count */
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 10px 0;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
<!-- Back Button -->
<a href="kennzahlen.php" class="back-button">Zurück zu den Kennzahlen</a>
<div class="header">
    <h2>Bro's Gemüse Kebap</h2>
    <p>Brennterwinkel 1</p>
    <p>88161 Lindenberg im Allgäu</p>
    <p>Tel: 08381/7478</p>
    <p>Datum: <?php echo $currentDateTime; ?></p>
    <hr>
</div>

<!-- Übersicht -->
<p><strong>Übersicht</strong></p>
<table class="totals-table">
    <tr>
        <td>Abholen</td>
        <td><?php echo "$abholenCount x"; ?></td>
        <td>EUR <?php echo number_format($abholenTotal, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Lieferung</td>
        <td><?php echo "$lieferungCount x"; ?></td>
        <td>EUR <?php echo number_format(($lieferungTotal - $lieferkostenTotal), 2, ',', '.'); ?></td>
    </tr>
	<tr>
        <td>Total ohne Lieferkosten</td>
		<td></td>
        <td>EUR <?php echo number_format($totalVorLiefern, 2, ',', '.'); ?></td>
    </tr>
	<tr>
        <td>zzgl. Fahrtkosten</td>
		<td></td>
        <td>EUR <?php echo number_format($lieferkostenTotal, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Stornos</td>
        <td><?php echo "$stornoCount x"; ?></td>
        <td>EUR <?php echo number_format($stornoTotal, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td><strong>Total</strong></td>
        <td></td>
        <td><strong>EUR <?php echo number_format($totalUebersicht, 2, ',', '.'); ?></strong></td>
    </tr>
</table>
<hr>

<!-- Zahlungsarten -->
<p><strong>Zahlungsarten</strong></p>
<table class="payment-table">
    <tr>
        <td>Bar</td>
        <td><?php echo $paymentMethods['Bar']['count'] ?? 0; ?></td>
        <td>EUR <?php echo number_format($barTotal, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>EC Karte (Terminal)</td>
        <td><?php echo $paymentMethods['Karte']['count'] ?? 0; ?></td>
        <td>EUR <?php echo number_format($ecTotal, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Rechnung</td>
        <td>0</td>
        <td>EUR 0,00</td>
    </tr>
</table>
<hr>
<hr>

<!-- Steueraufteilung -->
<p><strong>Bareinnahmen</strong></p>
<table class="tax-table">
	<tr>
        <td>Netto</td>
        <td>EUR <?php echo number_format($barNetto, 2, ',', '.'); ?></td>
    </tr>
	<tr>
        <td>7% MwSt</td>
        <td>EUR <?php echo number_format($tax7AmountBar, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>19% MwSt</td>
        <td>EUR <?php echo number_format($tax19AmountBar, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Brutto</td>
        <td>EUR <?php echo number_format($barTotal, 2, ',', '.'); ?></td>
    </tr>
</table>
<hr>

<p><strong>Kreditzahlungen</strong></p>
<table class="tax-table">
	<tr>
        <td>Netto</td>
        <td>EUR <?php echo number_format($ecNetto, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>7% MwSt</td>
        <td>EUR <?php echo number_format($tax7AmountEC, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>19% MwSt</td>
        <td>EUR <?php echo number_format($tax19AmountEC, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Brutto</td>
        <td>EUR <?php echo number_format($ecTotal, 2, ',', '.'); ?></td>
    </tr>
</table>
<hr>

<!-- Summen -->
<p><strong>Summen</strong></p>
<table class="totals-table">
    <tr>
        <td>Netto</td>
        <td>EUR <?php echo number_format($totalNetto, 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Gesamt MwSt</td>
        <td>EUR <?php echo number_format(($tax7AmountBar + $tax19AmountBar + $tax7AmountEC + $tax19AmountEC), 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td><strong>Brutto</strong></td>
        <td><strong>EUR <?php echo number_format($totalBrutto, 2, ',', '.'); ?></strong></td>
    </tr>
</table>

<div class="footer">
    <p>Erzeugt am <?php echo $currentDateTime; ?></p>
</div>

<script>
    window.print();
</script>

</body>
</html>
