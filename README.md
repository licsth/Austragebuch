# Digitales Austragebuch

Datenbankenprojekt Info-LK Q2 von Jule und Linda.

# Setup
## Dateistruktur
Um das digitale Austragebuch zum Laufen zu bringen, alle Dateien in einem Ordner auf einen Server laden bzw. in den htdocs-Ordner bei XAMPP kopieren.

## Datenbankstruktur
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
  isback BOOLEAN DEFAULT 0);
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

## Nutzerregistrierung

### Über Benutzeroberfläche
Der Ordner "Verworfen" enthält Dateien, die nicht in die eigentliche Seite integriert wurden, die aber trotzdem nützlich sein könnten, wie zum Beispiel die Registrierung von Schülern und SozPäds über eine benutzerfreundliche Oberfläche. Diese Funktion ist vollständig funktionsfähig und kann genutzt werden, indem ihr die Seite localhost/Austragebuch/Verworfen/schuelerregister.php bzw. sozpaedregister.php aufruft.

Wenn ihr die automatische Registrierfunktion benutzt, werden neue Schüler automatisch als vorname.nachname und SozPäds als ersterBuchstabeVorname.nachname registriert, das Passwort ist identisch mit dem Nutzernamen.

### Mit MySQL
Im Ordner "Sonstiges" befindet sich eine Liste mit korrekten SQL-Befehlen, um alle aktuellen Schüler der Q2 und Q4 zu registrieren. 

Anderweitig ist es ebenso möglich, Schüler und SozPäds manuell zu registrieren.

# Anmerkungen
#### Passwörter
Die Passwörter werden unverschlüsselt als reiner Text gespeichert. Wer dies ändern möchte, kann die Länge des Passwortes (pwd) anpassen und muss auf der loginp.php-Seite eine Verschlüsselung einbauen.

#### Servereigenschaften
Sollte euer Server nicht localhost heißen, oder habt ihr an den Zugangsdaten etwas verändert (also Benutzername nicht "root" und/oder Passwort nicht leer), könnt ihr dies in der Datei dbh.php ändern.
