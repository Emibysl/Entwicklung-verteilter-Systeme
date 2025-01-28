<?php
session_start();
if (!isset($_SESSION['benutzername'])) {
    header("Location: ../login.php");
    exit();
} else {
    echo "Willkommen, " . htmlspecialchars($_SESSION['benutzername']);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kennzahlen</title>
    <link rel="stylesheet" href="./assets/css/kennzahlen.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Kennzahlen Übersicht</h1>
        <a href="dashboardneu.php" class="back-button">Zurück zum Dashboard</a>
    </header>
    <main>
        <section>
            <h2>Datumswahl und Filter</h2>
            <form method="POST" action="kennzahlen.php">
                <label for="datePicker">Wählen Sie ein Datum:</label>
                <input type="date" id="datePicker" name="selected_date" value="<?= isset($_POST['selected_date']) ? htmlspecialchars($_POST['selected_date']) : date('Y-m-d'); ?>">
                
                <div class="button-group">
                    <button type="submit" name="today">Heutige Zahlen</button>
                    <button type="submit" name="totalAmounts">Tagesgesamtbeträge</button>
                    <button type="submit" name="paymentAnalysis">Zahlungsmethoden</button>
                </div>
                
                <label for="startDate">Zeitraum:</label>
                <input type="date" id="startDate" name="start_date">
                <input type="date" id="endDate" name="end_date">
                
                <label for="paymentType">Zahlungsmethode:</label>
                <select id="paymentType" name="payment_type">
                    <option value="">Alle</option>
                    <option value="Bar">Bar</option>
                    <option value="Karte">Karte</option>
                </select>

                <label for="orderType">Bestellstatus:</label>
                <select id="orderType" name="order_type">
                    <option value="">Alle</option>
                    <option value="Bestellt">Bestellt</option>
                    <option value="Geliefert">Geliefert</option>
                </select>

                <div class="button-group">
                    <button type="submit" name="applyFilters">Filter anwenden</button>
                    <button type="submit" name="periodPaymentAnalysis">Zeitraum-Zahlungsmethoden</button>
                    <button type="submit" name="topCustomers">Top-Kunden</button>
                </div>
            </form>
        </section>
		
		<!-- Neuer Abschnitt für den Tagesabschluss -->
        <section>
            <h2>Tagesabschluss</h2>
            <form method="GET" action="TagesabschlussErsteller.php">
                <label for="abschlussDatePicker">Wählen Sie das Datum für den Tagesabschluss:</label>
                <input type="date" id="abschlussDatePicker" name="selected_date" required>
                <button type="submit">Tagesabschluss drucken</button>
            </form>
        </section>
		
		<section>
		<h2>Monatsabschluss</h2>
			<form method="GET" action="MonatsabschlussErsteller.php">
				<label for="monthPicker">Wählen Sie einen Monat:</label>
				<input type="month" id="monthPicker" name="selected_month" required">
				<button type="submit">Monatsabschluss drucken</button>
			</form>
		</section>

        <section class="results">
            <!-- PHP Code to display analysis results dynamically here -->
			<?php
				include '../inc/dbverb.php';

				function getTotalAmountByOrderType($date = null) {
					global $conn;
					$dateCondition = $date ? "WHERE DATE(Bestelldatum) = '$date'" : "WHERE DATE(Bestelldatum) = CURDATE()";
					
					$sql = "SELECT bestell_typ, SUM(Gesamtbetrag) AS gesamtbetrag 
							FROM Bestellungentest 
							$dateCondition 
							GROUP BY bestell_typ";
					$result = mysqli_query($conn, $sql);
					$data = [];
					
					while ($row = mysqli_fetch_assoc($result)) {
						$data[] = $row;
					}
					
					return $data;
				}

				function getTotalAmounts($date) {
					global $conn;
					$sqlAbholung = "SELECT SUM(Gesamtbetrag) AS total_abholung FROM Bestellungentest WHERE bestell_typ = 'Abholen' AND DATE(Bestelldatum) = '$date'";
					$sqlBestellung = "SELECT SUM(Gesamtbetrag) AS total_bestellung FROM Bestellungentest WHERE bestell_typ = 'Liefern' AND DATE(Bestelldatum) = '$date'";
					$resultAbholung = mysqli_query($conn, $sqlAbholung);
					$resultBestellung = mysqli_query($conn, $sqlBestellung);
					
					$totalAbholung = mysqli_fetch_assoc($resultAbholung)['total_abholung'] ?? 0;
					$totalBestellung = mysqli_fetch_assoc($resultBestellung)['total_bestellung'] ?? 0;

					return ['total_abholung' => $totalAbholung, 'total_bestellung' => $totalBestellung];
				}

				function getPaymentMethodAnalysis($date = null) {
					global $conn;
					$dateCondition = $date ? "WHERE DATE(Bestelldatum) = '$date'" : "WHERE DATE(Bestelldatum) = CURDATE()";
					
					$sql = "SELECT Zahlungsmethode, COUNT(*) AS anzahl, SUM(Gesamtbetrag) AS gesamtbetrag 
							FROM Bestellungentest 
							$dateCondition 
							GROUP BY Zahlungsmethode";
					$result = mysqli_query($conn, $sql);
					$data = [];
					
					while ($row = mysqli_fetch_assoc($result)) {
						$data[] = $row;
					}
					
					return $data;
				}

				function getTopCustomers($limit = 5) {
					global $conn;
					$sql = "SELECT KundeName, COUNT(*) AS bestellungen, SUM(Gesamtbetrag) AS gesamtbetrag 
							FROM Bestellungentest 
							GROUP BY KundeName 
							ORDER BY gesamtbetrag DESC 
							LIMIT $limit";
					$result = mysqli_query($conn, $sql);
					$data = [];
					
					while ($row = mysqli_fetch_assoc($result)) {
						$data[] = $row;
					}
					
					return $data;
				}

				function getSalesOverTime($days = 30) {
					global $conn;
					$sql = "SELECT DATE(Bestelldatum) AS datum, SUM(Gesamtbetrag) AS gesamtbetrag 
							FROM Bestellungentest 
							WHERE Bestelldatum >= DATE_SUB(CURDATE(), INTERVAL $days DAY) 
							GROUP BY datum 
							ORDER BY datum ASC";
					$result = mysqli_query($conn, $sql);
					$data = [];
					
					while ($row = mysqli_fetch_assoc($result)) {
						$data[$row['datum']] = $row['gesamtbetrag'];
					}
					
					return $data;
				}
				
				function buildFilterConditions($start_date, $end_date, $payment_type, $order_type) {
					$conditions = [];
					if ($start_date && $end_date) {
						$conditions[] = "DATE(Bestelldatum) BETWEEN '$start_date' AND '$end_date'";
					} elseif ($start_date) {
						$conditions[] = "DATE(Bestelldatum) >= '$start_date'";
					} elseif ($end_date) {
						$conditions[] = "DATE(Bestelldatum) <= '$end_date'";
					} else {
						$conditions[] = "DATE(Bestelldatum) = CURDATE()";
					}
					
					if ($payment_type) {
						$conditions[] = "Zahlungsmethode = '$payment_type'";
					}
					
					if ($order_type) {
						$conditions[] = "bestell_typ = '$order_type'";
					}
					
					return "WHERE " . implode(" AND ", $conditions);
				}


				$selectedDate = isset($_POST['selected_date']) ? $_POST['selected_date'] : date('Y-m-d'); 

				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					if (isset($_POST['today'])) {
						$salesData = getTotalAmountByOrderType(); // Heute
						echo "<h2>Verkäufe für heute:</h2>";
					} elseif (isset($_POST['selectDate'])) {
						$salesData = getTotalAmountByOrderType($selectedDate); // Ausgewähltes Datum
						echo "<h2>Verkäufe für den $selectedDate:</h2>";
					} elseif (isset($_POST['totalAmounts'])) {
						$totals = getTotalAmounts($selectedDate); 
						echo "<h2>Gesamtbeträge für den $selectedDate:</h2>";
						echo "<p>Gesamtbetrag der Abholungen: {$totals['total_abholung']} €</p>";
						echo "<p>Gesamtbetrag der Bestellungen: {$totals['total_bestellung']} €</p>";
					} elseif (isset($_POST['paymentAnalysis'])) {
						$paymentAnalysis = getPaymentMethodAnalysis($selectedDate);
						echo "<h2>Zahlungsmethoden-Analyse für den $selectedDate:</h2>";
						echo "<table border='1'>";
						echo "<tr><th>Zahlungsmethode</th><th>Anzahl</th><th>Gesamtbetrag</th></tr>";
						foreach ($paymentAnalysis as $payment) {
							echo "<tr><td>{$payment['Zahlungsmethode']}</td><td>{$payment['anzahl']}</td><td>{$payment['gesamtbetrag']} €</td></tr>";
						}
						echo "</table>";
					} elseif (isset($_POST['topCustomers'])) {
						$topCustomers = getTopCustomers();
						echo "<h2>Top-Kunden:</h2>";
						echo "<table border='1'>";
						echo "<tr><th>Kundenname</th><th>Bestellungen</th><th>Gesamtbetrag</th></tr>";
						foreach ($topCustomers as $customer) {
							echo "<tr><td>{$customer['KundeName']}</td><td>{$customer['bestellungen']}</td><td>{$customer['gesamtbetrag']} €</td></tr>";
						}
						echo "</table>";
					} elseif (isset($_POST['applyFilters'])) {
						$startDate = $_POST['start_date'];
						$endDate = $_POST['end_date'];
						$paymentType = $_POST['payment_type'];
						$orderType = $_POST['order_type'];
						
						$filterConditions = buildFilterConditions($startDate, $endDate, $paymentType, $orderType);

						$sql = "SELECT bestell_typ, SUM(Gesamtbetrag) AS gesamtbetrag FROM Bestellungentest $filterConditions GROUP BY bestell_typ";
						$result = mysqli_query($conn, $sql);
						$filteredData = [];
						
						while ($row = mysqli_fetch_assoc($result)) {
							$filteredData[] = $row;
						}
						
						echo "<h2>Gefilterte Verkäufe:</h2>";
						if (!empty($filteredData)) {
							echo "<table border='1'>";
							echo "<tr><th>Bestelltyp</th><th>Gesamtbetrag</th></tr>";
							foreach ($filteredData as $data) {
								echo "<tr><td>{$data['bestell_typ']}</td><td>{$data['gesamtbetrag']} €</td></tr>";
							}
							echo "</table>";
						} else {
							echo "<p>Keine Verkäufe für die ausgewählten Filterkriterien gefunden.</p>";
						}
					} elseif (isset($_POST['periodPaymentAnalysis'])) {
						$startDate = $_POST['start_date'];
						$endDate = $_POST['end_date'];
						$paymentType = $_POST['payment_type'];
						$orderType = $_POST['order_type'];

						// Filterkriterien basierend auf dem Zeitraum erstellen
						$filterConditions = buildFilterConditions($startDate, $endDate, $paymentType, $orderType);
						
						// SQL-Abfrage für Zahlungsmethoden innerhalb des Zeitraums
						$sql = "SELECT Zahlungsmethode, COUNT(*) AS anzahl, SUM(Gesamtbetrag) AS gesamtbetrag 
								FROM Bestellungentest 
								$filterConditions 
								GROUP BY Zahlungsmethode";
						$result = mysqli_query($conn, $sql);
						$periodPaymentData = [];

						while ($row = mysqli_fetch_assoc($result)) {
							$periodPaymentData[] = $row;
						}

						echo "<h2>Zahlungsmethoden für den Zeitraum $startDate bis $endDate:</h2>";
						if (!empty($periodPaymentData)) {
							echo "<table border='1'>";
							echo "<tr><th>Zahlungsmethode</th><th>Anzahl</th><th>Gesamtbetrag</th></tr>";
							foreach ($periodPaymentData as $payment) {
								echo "<tr><td>{$payment['Zahlungsmethode']}</td><td>{$payment['anzahl']}</td><td>{$payment['gesamtbetrag']} €</td></tr>";
							}
							echo "</table>";
						} else {
							echo "<p>Keine Zahlungsmethoden für den ausgewählten Zeitraum gefunden.</p>";
						}
					}



					// Umsatzdarstellung über die Zeit
					$salesOverTime = getSalesOverTime();
					echo "<h2>Umsatz über die letzten 30 Tage:</h2>";
					echo '<canvas id="salesChart" style="width: 300px; height: 150px;"></canvas>';
					echo "<script>
							const ctx = document.getElementById('salesChart').getContext('2d');
							const labels = " . json_encode(array_keys($salesOverTime)) . ";
							const data = " . json_encode(array_values($salesOverTime)) . ";
							const myChart = new Chart(ctx, {
								type: 'line',
								data: {
									labels: labels,
									datasets: [{
										label: 'Umsatz (€)',
										data: data,
										borderColor: 'rgba(75, 192, 192, 1)',
										borderWidth: 2,
										fill: false
									}]
								},
								options: {
									responsive: true,
									scales: {
										y: {
											beginAtZero: true
										}
									}
								}
							});
						  </script>";

					// Ausgabe der Verkaufsdaten
					if (!empty($salesData)) {
						echo "<table border='1'>";
						echo "<tr><th>Bestelltyp</th><th>Gesamtbetrag</th></tr>";
						foreach ($salesData as $data) {
							echo "<tr><td>{$data['bestell_typ']}</td><td>{$data['gesamtbetrag']} €</td></tr>";
						}
						echo "</table>";
					}
				}
				?>
        </section>
    </main>
</body>
</html>

