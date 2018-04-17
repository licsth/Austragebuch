# Digitales Austragebuch
Datenbankenprojekt Info-LK Q2 von Jule und Linda.
# Setup
### Dateistruktur
Um das digitale Austragebuch zum Laufen zu bringen, alle Dateien in einem Ordner auf einen Server laden bzw. in den htdocs-Ordner bei XAMPP kopieren.

### Datenbankstruktur
Eine neue Datenbank mit Namen "Austragebuch" erstellen:
``` MySQL
CREATE DATABASE Austragebuch;
```
Diese Datenbank umfasst die Tabellen eintrag, schueler, sozpaed und gast.
Für die Eintrag-Tabelle:
```MySQL
CREATE TABLE eintrag(
  id INT AUTO_INCREMENT PRIMARY KEY,
  uid VARCHAR(100) NOT NULL,
  wg VARCHAR(3) NOT NULL,
  away DATETIME DEFAULT CURRENT_TIMESTAMP,
  back VARCHAR(100) NOT NULL,
  absprache VARCHAR(50) DEFAULT NULL,
  wohin VARCHAR(100) NOT NULL,
  isback BOOLEAN DEFAULT NULL);
```
Für die Schüler-Tabelle:
```MySQL
CREATE TABLE schueler(
  uid VARCHAR(100) NOT NULL PRIMARY KEY,
  pwd VARCHAR(100) NOT NULL,
  first VARCHAR(50) NOT NULL,
  last VARCHAR(50) NOT NULL,
  wg VARCHAR(3) NOT NULL,
  ausgetragen BOOLEAN DEFAULT NULL);
```
Für die SozPäd-Tabelle:
```MySQL
CREATE TABLE sozpaed(
  uid VARCHAR(100) NOT NULL PRIMARY KEY,
  pwd VARCHAR(100) NOT NULL,
  first VARCHAR(50) NOT NULL,
  last VARCHAR(50) NOT NULL);
```
Für die Gäste-Tabelle:
```MySQL
CREATE TABLE gast(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  zeitraum VARCHAR(200) NOT NULL,
  schueler_uid VARCHAR(100) NOT NULL,
  bestaetigt BOOLEAN DEFAULT NULL,
  antrag DATETIME DEFAULT CURRENT_TIMESTAMP,
  aktuell BOOLEAN DEFAULT 1);
```

#### Anmerkungen
Die Passwörter werden unverschlüsselt als reiner Text gespeichert. Wer dies ändern möchte, kann die Länge des Passwortes (pwd) anpassen und muss auf der loginp.php-Seite eine Verschlüsselung einbauen.

Sollte euer Server nicht localhost heißen, oder habt ihr an den Zugangsdaten etwas verändert (also Benutzername nicht "root" und/oder Passwort nicht leer), könnt ihr dies in der Datei dbh.php ändern.
