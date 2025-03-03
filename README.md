# Foody - Restaurant Dashboard and Delivery Platform

## Overview

Foody ist eine Softwarekomponente, die im Rahmen des Kurses "Entwicklung verteilter Systeme" entwickelt wurde. Das System dient als Dashboard für Restaurantbesitzer zur Verwaltung von Speisekarten, Bestellungen und Lieferbereichen. Es wird davon ausgegangen, dass der Restaurantbesitzer bereits einen Account erstellt hat und erste Speisen sowie Lieferbereiche hinzugefügt wurden.

## Features

- **Restaurant Dashboard:** Verwalten von Speisekarten, Produkten und Bestellungen.
- **Delivery Management:** Bearbeitung und Verwaltung von Lieferbereichen.
- **Order Management:** Echtzeitübersicht über eingehende Bestellungen.
- **Benutzerfreundliche Oberfläche:** Responsive Design, das auf verschiedenen Endgeräten funktioniert.
- **Verteilte Architektur:** Modularer Aufbau mit separaten PHP-Skripten und einer MySQL-Datenbank.

## Voraussetzungen

- **XAMPP:** 
  - XAMPP wird verwendet, um Apache, PHP und MySQL lokal auszuführen.
  - Der Administrator der Datenbank erfolgt über phpMyAdmin.
- **Git:** Für die Versionskontrolle und das Pushen in das Repository der Professoren.
- **Grundkenntnisse in PHP und MySQL.**

## Installation

1. **XAMPP herunterladen und installieren:**
   - Lade XAMPP von [Apache Friends](https://www.apachefriends.org/index.html) herunter und installiere es.
   - Starte den Apache- und MySQL-Dienst über das XAMPP Control Panel.

2. **Projekt klonen:**
   - Klone das Projekt in das `htdocs`-Verzeichnis von XAMPP:
     ```bash
     git clone <repository-url> foody
     ```
   - Navigiere in den neu erstellten Ordner `foody`.

3. **Datenbank einrichten:**
   - Öffne deinen Browser und gehe zu [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Erstelle eine neue Datenbank, z.B. `foody_db`.
   - Importiere die SQL-Dump-Datei (z.B. `database.sql`), die im Projekt enthalten ist, in diese Datenbank.
   - Passe gegebenenfalls die Datenbankverbindungsdaten in `inc/dbverb.php` an.

4. **Projekt konfigurieren:**
   - Stelle sicher, dass der Ordner `uploads` beschreibbar ist, damit Bilder korrekt hochgeladen werden können.
   - Überprüfe, ob die Pfade in `inc/dbverb.php` und `inc/functions.php` korrekt sind.

## Nutzung

- **Zugriff auf das Dashboard:**
  - Öffne im Browser [http://localhost/foody/mainDashboard.php](http://localhost/foody/mainDashboard.php) (bzw. den entsprechenden Pfad in deinem lokalen Setup).

- **Verwaltung:**
  - Restaurantbesitzer können über das Dashboard Produkte bearbeiten, hinzufügen oder löschen sowie Lieferbereiche verwalten.
  - Es wird davon ausgegangen, dass ein Restaurant-Besitzer bereits einen Account besitzt und erste Produkte und Lieferbereiche hinzugefügt hat.

## Projektstruktur

- **inc/**  
  Enthält PHP-Includes wie `dbverb.php` (Datenbankverbindung) und `functions.php` (Hilfsfunktionen, z.B. `escape()`).

- **process/**  
  Enthält Verarbeitungsskripte wie `process_product.php`.

- **assets/**  
  - **css/**: CSS-Dateien für das Styling.
  - **js/**: JavaScript-Dateien.
  - **sounds/**: Audiodateien, z.B. für Bestellbenachrichtigungen.

- **uploads/**  
  Ordner für hochgeladene Bilder.

- **README.md**  
  Diese Datei.

## Deployment

- **Push ins Professoren-Repository:**  
  Wenn du dein Projekt abgeben möchtest, erstelle ein neues Repository (z.B. `Foody_Submission`) und kopiere die Dateien ohne den kompletten Commit-Verlauf (z.B. mittels Squash-Merge oder durch erneutes Committen der sauberen Dateien).

- **Verbergen von Änderungen:**  
  Falls du nicht möchtest, dass alle Änderungen sichtbar sind, kannst du ein neues, sauberes Repository erstellen und nur den finalen Stand hochladen.

## Hinweise zur Nutzung von XAMPP

- **Datenbankverwaltung:**  
  XAMPP beinhaltet phpMyAdmin, über das du deine MySQL-Datenbank verwalten kannst. Stelle sicher, dass du die Zugangsdaten in `inc/dbverb.php` entsprechend anpasst.
  
- **Server starten:**  
  Öffne das XAMPP Control Panel und starte Apache und MySQL, bevor du das Projekt im Browser öffnest.

## License

Dieses Projekt dient ausschließlich zu Lehrzwecken. © 2024 [Dein Universitätsname].

---

