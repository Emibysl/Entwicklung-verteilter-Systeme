<?php
// Leerzeichen loswerden
function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// XSS verhindern
function escape($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}


// In deliveryZones.php (Städte und Zonen aus der DB holen)
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

// In mainDashboard.php (Produkte nach Kategorie aus der DB holen)
function getProductsByCategory($conn, $searchTerm = '')
{
    if (!empty($searchTerm)) {
        $sql = "SELECT * FROM produkte WHERE name LIKE ? ORDER BY kategorie";
        $stmt = $conn->prepare($sql);
        $search = "%" . $searchTerm . "%";
        $stmt->bind_param('s', $search);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        $sql = "SELECT * FROM produkte ORDER BY kategorie";
        $result = $conn->query($sql);
    }
// Produkte in die Kategorien füllen
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
        return false;
    }
}

// In orders.php (heutige Bestellungen aus der DB holen)
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
