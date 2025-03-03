<?php
//process_product.php
function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function escape($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}


//deliveryZones.php
function getCities($conn)
{
    $sql = "SELECT id, stadt_name, zone_id FROM staedte";
    return $conn->query($sql);
}

function getZones($conn)
{
    $sql = "SELECT * FROM zonen";
    return $conn->query($sql);
}

//mainDashboard.php
function getProductsByCategory($conn, $searchTerm = '')
{
    if (!empty($searchTerm)) {
        $sql = "SELECT * FROM produkte WHERE name LIKE ? ORDER BY kategorie";
        $stmt = $conn->prepare($sql);
        $wildSearch = "%" . $searchTerm . "%";
        $stmt->bind_param('s', $wildSearch);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        $sql = "SELECT * FROM produkte ORDER BY kategorie";
        $result = $conn->query($sql);
    }

    $productsByCategory = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $category = $row['kategorie'];
            if (!isset($productsByCategory[$category])) {
                $productsByCategory[$category] = [];
            }
            $productsByCategory[$category][] = $row;
        }
        return $productsByCategory;
    } else {
        return false; // oder [] je nach gewünschtem Verhalten
    }
}

//orders.php
function getTodayOrders($conn)
{
    $sql = "SELECT b.*,
            GROUP_CONCAT(
                CONCAT('<br>- ', bp.Menge, 'x ', bp.ProduktName)
                SEPARATOR ''
            ) AS Lieferinhalt
            FROM Bestellungentest b
            LEFT JOIN Bestellpositionen bp ON b.BestellungID = bp.BestellungID
            WHERE DATE(b.Bestelldatum) = CURDATE()
            GROUP BY b.BestellungID";
    return $conn->query($sql);
}

/*
function getDailyOverview($conn, $selectedDate) {
    $overview = [];

    // Gesamtbetrag und Anzahl der Bestellungen
    $sqlTotal = "SELECT SUM(Gesamtbetrag) AS gesamtbetrag, COUNT(*) AS anzahl FROM Bestellungentest WHERE DATE(Bestelldatum) = ?";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->bind_param("s", $selectedDate);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result();
    $dataTotal = $resultTotal->fetch_assoc();
    $stmtTotal->close();

    $overview['totalAmount'] = $dataTotal['gesamtbetrag'] ?? 0;
    $overview['totalOrders'] = $dataTotal['anzahl'] ?? 0;

    // Bestellungen nach Typ (Abholen vs. Liefern)
    $sqlOrderTypes = "SELECT bestell_typ, SUM(Gesamtbetrag) AS gesamtbetrag, COUNT(*) AS anzahl 
                      FROM Bestellungentest WHERE DATE(Bestelldatum) = ? GROUP BY bestell_typ";
    $stmtOrderTypes = $conn->prepare($sqlOrderTypes);
    $stmtOrderTypes->bind_param("s", $selectedDate);
    $stmtOrderTypes->execute();
    $resultOrderTypes = $stmtOrderTypes->get_result();

    $abholenTotal = $lieferungTotal = 0;
    $abholenCount = $lieferungCount = 0;
    while ($row = $resultOrderTypes->fetch_assoc()) {
        if ($row['bestell_typ'] === 'Abholen') {
            $abholenTotal = $row['gesamtbetrag'];
            $abholenCount = $row['anzahl'];
        } elseif ($row['bestell_typ'] === 'Liefern') {
            $lieferungTotal = $row['gesamtbetrag'];
            $lieferungCount = $row['anzahl'];
        }
    }
    $stmtOrderTypes->close();

    $overview['abholenTotal'] = $abholenTotal;
    $overview['abholenCount'] = $abholenCount;
    $overview['lieferungTotal'] = $lieferungTotal;
    $overview['lieferungCount'] = $lieferungCount;

    // Lieferkosten (nur für "Liefern")
    $sqlDeliveryCosts = "SELECT SUM(Lieferkosten) AS lieferkosten FROM Bestellungentest WHERE DATE(Bestelldatum) = ? AND bestell_typ = 'Liefern'";
    $stmtDeliveryCosts = $conn->prepare($sqlDeliveryCosts);
    $stmtDeliveryCosts->bind_param("s", $selectedDate);
    $stmtDeliveryCosts->execute();
    $resultDeliveryCosts = $stmtDeliveryCosts->get_result();
    $dataDeliveryCosts = $resultDeliveryCosts->fetch_assoc();
    $stmtDeliveryCosts->close();

    $lieferkostenTotal = $dataDeliveryCosts['lieferkosten'] ?? 0;
    $overview['lieferkostenTotal'] = $lieferkostenTotal;

    // Gesamt vor Lieferkosten und Gesamtübersicht berechnen
    $totalVorLiefern = $abholenTotal + ($lieferungTotal - $lieferkostenTotal);
    $overview['totalVorLiefern'] = $totalVorLiefern;
    $overview['totalUebersicht'] = $totalVorLiefern + $lieferkostenTotal;

    return $overview;
}

// Holt die Zahlungsübersicht und berechnet Steuerwerte.
 
function getPaymentOverview($conn, $selectedDate, $taxRate = 7) {
    $paymentOverview = [];

    $sqlPayments = "SELECT Zahlungsmethode, SUM(Gesamtbetrag) AS gesamtbetrag, COUNT(*) AS anzahl 
                    FROM Bestellungentest WHERE DATE(Bestelldatum) = ? GROUP BY Zahlungsmethode";
    $stmtPayments = $conn->prepare($sqlPayments);
    $stmtPayments->bind_param("s", $selectedDate);
    $stmtPayments->execute();
    $resultPayments = $stmtPayments->get_result();
    $paymentMethods = [];
    while ($row = $resultPayments->fetch_assoc()) {
        $paymentMethods[$row['Zahlungsmethode']] = [
            'amount' => $row['gesamtbetrag'],
            'count'  => $row['anzahl']
        ];
    }
    $stmtPayments->close();
    $paymentOverview['paymentMethods'] = $paymentMethods;

    $barTotal = $paymentMethods['Bar']['amount'] ?? 0;
    $ecTotal = $paymentMethods['Karte']['amount'] ?? 0;
    $paymentOverview['barTotal'] = $barTotal;
    $paymentOverview['ecTotal'] = $ecTotal;

    $tax7AmountBar = $barTotal / (1 + $taxRate / 100) * ($taxRate / 100);
    $barNetto = $barTotal - $tax7AmountBar;
    $tax7AmountEC = $ecTotal / (1 + $taxRate / 100) * ($taxRate / 100);
    $ecNetto = $ecTotal - $tax7AmountEC;
    $paymentOverview['tax7AmountBar'] = $tax7AmountBar;
    $paymentOverview['barNetto'] = $barNetto;
    $paymentOverview['tax7AmountEC'] = $tax7AmountEC;
    $paymentOverview['ecNetto'] = $ecNetto;

    $totalNetto = $barNetto + $ecNetto;
    $totalBrutto = $barTotal + $ecTotal;
    $paymentOverview['totalNetto'] = $totalNetto;
    $paymentOverview['totalBrutto'] = $totalBrutto;

    return $paymentOverview;
}

// Funktion, um die Monats-Totals (Summe und Anzahl Bestellungen) abzurufen
function getMonthlyTotals($conn, $startOfMonth, $endOfMonth) {
    $sql = "SELECT SUM(Gesamtbetrag) AS gesamtbetrag, COUNT(*) AS anzahl FROM Bestellungentest WHERE DATE(Bestelldatum) BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startOfMonth, $endOfMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

// Funktion, um die Summen und Anzahlen pro Bestelltyp abzurufen
function getOrderTypesTotals($conn, $startOfMonth, $endOfMonth) {
    $sql = "SELECT bestell_typ, SUM(Gesamtbetrag) AS gesamtbetrag, COUNT(*) AS anzahl FROM Bestellungentest WHERE DATE(Bestelldatum) BETWEEN ? AND ? GROUP BY bestell_typ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startOfMonth, $endOfMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderTypes = [];
    while ($row = $result->fetch_assoc()) {
        $orderTypes[$row['bestell_typ']] = $row;
    }
    $stmt->close();
    return $orderTypes;
}

// Funktion, um die gesamten Lieferkosten für den Monat abzurufen
function getDeliveryCosts($conn, $startOfMonth, $endOfMonth) {
    $sql = "SELECT SUM(Lieferkosten) AS lieferkosten FROM Bestellungentest WHERE DATE(Bestelldatum) BETWEEN ? AND ? AND bestell_typ = 'Liefern'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startOfMonth, $endOfMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data['lieferkosten'] ?? 0;
}

// Funktion, um die Zahlungsarten (Summe und Anzahl) abzurufen
function getPaymentMethods($conn, $startOfMonth, $endOfMonth) {
    $sql = "SELECT Zahlungsmethode, SUM(Gesamtbetrag) AS gesamtbetrag, COUNT(*) AS anzahl FROM Bestellungentest WHERE DATE(Bestelldatum) BETWEEN ? AND ? GROUP BY Zahlungsmethode";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startOfMonth, $endOfMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $methods = [];
    while ($row = $result->fetch_assoc()) {
        $methods[$row['Zahlungsmethode']] = [
            'amount' => $row['gesamtbetrag'],
            'count'  => $row['anzahl']
        ];
    }
    $stmt->close();
    return $methods;
*/
