# Foody - Restaurant Dashboard and Delivery Platform

## Overview

Dies ist eine Softwarekomponente des Projekts „Foody“, die im Rahmen des Kurses „Entwicklung verteilter Systeme“ entwickelt wurde. Sie dient als Dashboard für Restaurantbesitzer zur Verwaltung von Speisekarten, Lieferbereichen und Wartezeiten sowie der Ansicht von Bestellungen. Es wird davon ausgegangen, dass der Restaurantbesitzer bereits einen Account erstellt hat und erste Speisen sowie Lieferbereiche hinzugefügt wurden.

## Features

- **Speisekartenverwaltung:** Löschen und Bearbeiten von Produkten aus der Speisekarte.
- **Offene Bestellungen:** Alle Bestellungen des heutigen Tages.
- **Neues Produkt anlegen:** Hinzufügen von Produkten zur Speisekarte.
- **Wartezeit anpassen:** Anpassung der Wartezeit, die den Kunden angezeigt werden soll.
- **Lieferbereiche anpassen:** Anpassung der Zonen und Städte, in die geliefert werden soll inkl. Preisänderungen, Mindestbestellwerten und Lieferkosten.

Auf die Erstellung von Belegen und Tages/Monatsabschlüssen wurde in dieser Komponente verzichtet, da dies den Rahmen sprengen würde und außerhalb des Fähigkeitsbereichs meiner selbst liegt.

## Voraussetzungen

- **XAMPP**
  - XAMPP wird verwendet, um Apache, PHP und MySQL lokal auszuführen.
  - Der Administrator der Datenbank erfolgt über phpMyAdmin.

## Nutzung

- **Zugriff auf das Dashboard:**
  - Öffne im Browser [http://localhost/foody/mainDashboard.php](http://localhost/foody/mainDashboard.php) (bzw. den entsprechenden Pfad in deinem lokalen Setup).

- **Verwaltung:**
  - Restaurantbesitzer können über das Dashboard Produkte bearbeiten, hinzufügen oder löschen sowie Lieferbereiche verwalten.

- **Wichtiger Hinweis zu Bestellungen:**
  - Damit Bestellungen in der Bestellübersicht angezeigt werden, müssen in der Tabelle `Bestellungentest` die Datumswerte der Bestellungen auf den heutigen Tag umgestellt werden. Andernfalls erscheinen diese Bestellungen nicht in der Übersicht der aktuellen Bestellungen.

## Schnittstellen und Integration mit anderen Komponenten

- **Login und Restaurant-Account:**
  - In dieser Komponente ist kein Login implementiert, da die Authentifizierung und Benutzerverwaltung von einem anderen Teammitglied realisiert wird. 
  - Restaurantbesitzer loggen sich in ihren individuellen Restaurant-Account ein, wodurch sie Zugriff auf ihr Dashboard erhalten.

- **Startseite und Speisekartenanzeige:**
  - Es gibt eine separate Startseite, auf der alle Restaurants aufgelistet werden.
  - Beim Anklicken eines Restaurants gelangt der Benutzer zur entsprechenden Speisekarte, die vom Restaurantbesitzer verwaltet wird. Diese Speisekarte ist identisch mit der im Dashboard bearbeiteten.

- **Lieferantentracking:**
  - Eine zusätzliche Softwarekomponente zur Verfolgung der Lieferanten ist ebenfalls Bestandteil des Gesamtprojekts.
  - Durch eine Freigabe der Bestellung im Restaurant-Dashboard erhält der Kunde Zugriff auf den Standort des Lieferfahrers und kann den Status seiner Bestellung nachverfolgen. Aufgrund der Fokussierung dieser Komponente auf die Verwaltung von Speisekarten, Lieferbereichen und Bestellungen wurde die Freigabe einer Bestellung aus Vereinfachungsgründen nicht direkt integriert.

## Datenbankkonfiguration

Damit das Projekt auf eurem Rechner ausgeführt werden kann, müssen folgende Schritte für die Datenbank durchgeführt werden:

1. **Datenbank erstellen:**
   - XAMPP starten und phpMyAdmin öffnen.
   - Neue Datenbank erstellen, z. B. `foody_db`.

2. **SQL-Dump importieren:**
   - Im Projekt liegt eine SQL-Dump-Datei (`database.sql`) bei, die alle notwendigen Tabellen und Testdaten enthält.
   - In phpMyAdmin die neu erstellte Datenbank auswählen und Daten importieren.

3. **Datenbankverbindung anpassen:**
   - Die Datenbankverbindung wird in der Datei `inc/dbverb.php` konfiguriert:

     ```php
     <?php
     $servername = "localhost";
     $username = "root";  // Standardbenutzer in XAMPP
     $password = "";      // Standardmäßig kein Passwort
     $dbname = "foody_db";// Hier den Namen eintragen, den ihr der Datenbank gegeben habt

     $conn = new mysqli($servername, $username, $password, $dbname);

     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
     ?>
     ```

## Projektstruktur

- **assets/**
  - **css/**: CSS-Dateien für das Styling.
  - **js/**: JavaScript-Dateien.

- **inc/**  
  Enthält PHP-Includes wie `dbverb.php` (Datenbankverbindung) und `functions.php` (Hilfsfunktionen, z.B. `escape()`).

- **process/**  
  Enthält Verarbeitungsskripte wie `process_product.php`.

- **TimmUndJulian/**
  Enthält die SQL-Dump-Datei.

- **uploads/**  
  Ordner für Produktbilder.

---
