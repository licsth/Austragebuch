# Dokumentation Austragebuch

Das digitale Austragebuch ist ein Vorschlag als Alternative zum aktuellen Austragebuch in Papierform. Es soll es den Schülern erleichtern, sich vom Schul- und Internatsgelände auszutragen, indem wir eine Webseite erstellt haben, die über eine Anbindung an eine Datenbank Einträge der Schüler speichert und verwaltet und sie den SozPäds anzeigt. Darüber hinaus verfügt das digitale Austragebuch über ein System zur Verwaltung von Besuchsankündigungen und dem Postdienst.

Zusätzlich zu dem Webservice sind die Funktionen zum Austragebuch und Postdienst ebenfalls über einen Telegram-Bot verfügbar.

### Table of contents

1. [Die Idee](#die-idee)

2. [Umsetzung](#umsetzung)

3. [Probleme](#probleme)

4. [Arbeitsteilung](#arbeitsteilung)

5. [Lernerfolg](#lernerfolg)

## Die Idee

Die Idee des Projektes war es, das Austragebuch, welches am Internat benutzt wird, um festzuhalten, wer das Gelände verlässt, wohin er geht und wann er wiederkommt, zu digitalisieren. Es sollten also alle Schüler einen Account haben, mit dem sie sich online einloggen können um sich auszutragen. Die Sozialpädagogen sollten zudem Zugriff auf die Daten erhalten, um beispielsweise in einem Brandfall die Anwesenheit korrekt feststellen zu können.

Später kam zu dieser Grundidee unsere Unzufriedenheit mit der Zettelwirtschaft, die im Internat für die meisten Prozesse betrieben wird. So gibt es unterschiedliche Formulare für die Anmeldung von Besuch oder um Mängel und Defekte zu melden. Diese können nun ebenfalls über die Datenbank ausgefüllt und verarbeitet werden.

Die nächste Idee resultierte aus der leichten Ineffizienz, die die momentane Regelung des Postdienstes mit sich bringt. Im Moment wird in der Theorie einmal am Tag in die WhatsApp-Gruppe der gesamten Schülerschaft geschrieben, für wen Pakete gekommen sind und wo sich diese im Moment befinden. Demnach haben wir auch den Postdienst im Austragebuch zusätzlich implementiert.

Da jedoch auch das Aufrufen einer solchen Webseite recht zeitintensiv ist und auch Zeitdruck in unserer Überlegung das Austragebuch zu digitalisieren eine große Rolle gespielt hat, haben wir uns hierfür eine Alternative überlegt. Zur Erleichterung dieser Prozesse haben wir also einen Telegram-Bot programiert, da Telegram aufgrund des hohen Stellenwerts von Datenverschlüsselung am Hansenberg recht beliebt ist und es eine gut dokumentierte API, um Bots zu entwickeln, gibt, welche uns den Weg geebnet hat.

## Umsetzung

#### Die Webseite

##### Grundstruktur / Austragen & Eintragen

Zu Beginn mussten wir eine Struktur für die Datenbank festlegen, welche den Ansprüchen an die Funktionalitäten genügt. Angefangen hat die Umsetzung mit drei Entitäten:

```sql
Schueler(uid, name, vorname, passwort, wg),
Sozpaed(uid, name, vorname, passwort),
Eintrag(id, zeit, wohin, zurueck, absprache, ↑schueler_uid)
```

Es gibt also Datenbankenobjekte für Sozialpädagogen und Schüler.Schüler erstellen einen Eintrag, wenn sie das Gelände verlassen _(austragen.php)_. Hierfür nimmt die Datenbank automatisch einen Zeitstempel von der Zeit des Eintrages und speichert den Nutzernamen (uid) des Schülers, sodass dieser nur noch eingeben muss, wohin er geht, wann er zurück ist und wenn nötig, mit welchem Sozialpädagogen dies abgesprochen ist. Wie alle Skripte zur Auswertung von Formularen etc. findet sich das zugehörige Skript im Unterordner _processing_  des Schüler-Ordners _(processing/austragenp.php)_

Im Verlauf dessen haben wir die Umwandlung aller uns bekannten Eingabeformen in PHP-DateTime-Objekte implementiert, sodass diese in ein Zeitformat überführt werden kann, welches dann in der Datenbank gespeichert wird. Dies diente der Benutzbarkeit und intuitiven Bedienung, sowie der Effizienz im Vergleich zum papierbasierten Austragebuch, welches zurzeit verwendet wird. Hierfür haben wir uns entschieden, da ein DateTime-Objekt gegenüber einer reinen Texteingabe viele Vorteile hat, da es zum Beispiel ein universelles Format besitzt ist und somit einfacher weiterverwendet und verstanden werden kann, z.B. um Daten zu formatieren und auch nach Aktualität unterschiedlich zu behandeln.

##### Nutzeraccounts

Für den Eintrage-Prozess sind demnach die einzelnen Accounts der Schüler gefordert, um eine indivuduelle Web-Anbindung zu schaffen, für die wir noch einige Informationen brauchen.

Die uid des Schülers setzt sich automatisch aus Vornamen und Nachnamen zusammen nach dem Schema vorname.nachname. Dies ist auch das Passwort, welches am Anfang generiert wird. Für die Sozialpädagogen wurde eine ähnliche Vorgehensweise benutzt. Hier setzt sich beides aus dem ersten Buchstaben des Vornamens und dem Nachnamen zusammen. Also: v.nachname, wie auch bei den offiziellen Hansenberg-E-Mail-Adressen. Für die jeweiligen Accounts gibt es je nach Rolle auf der Webseite eigene Startseiten, um zur Übersichtlichkeit und Bedienungsfreundlichkeit beizutragen _(schueler.php bzw. sozpaed.php)_

An dieser Stelle haben wir auch die Möglichkeit, sein Passwort zu ändern, eingebaut. Hierfür gibt es eine Seite, die unter der Option Account in einem Dropdown-Menü zu öffnen ist _(password.php)_. Dort steht ein Formular zu Verfügung, in das zur Kontrolle das alte und dann das neue Passwort eigegeben werden. Das neue Passwort wird dann in gehashter Form in der Datenbank eingetragen, um die Sicherheit der Passwörter zu garantieren _(processing/pwd.php)_. Danach erfolgt eine Umleitung auf die Startseite, auf der man durch ein Bootstrap-generiertes Banner darauf hingewiesen wird, dass die Änderung des Passwortes erfolgreich durchgeführt wurde.

Zusätzlich gibt es die Option, sein Passwort durch die Sozialpädagogen zurücksetzen zu lassen _(schueler-passwort.php)_. Dafür gibt dieser den Namen des Schülers unter dem Menüpunkt "Schülerpasswort" direkt unter dem Menüpunkt zum Ändern des eigenen Passworts in das bereitstehende Feld ein. Das Passwort ist dann wieder indentisch der uid _(Skript unter processing/schueler-passwort.php)_. Hier gibt es ein Auto-Complete für die vervollständigung der Schülernamen, das allerdings noch ausbaufähig ist, da es zum Zeitpunkt unserer Tests nicht auf allen Betriebssystem funktionierte.

Grundsätzlich lässt sich sagen, dass wir auf allen Seiten - mit Ausnahme der Anmeldeseite – zuerst überprüfen, ob der Nutzer angemeldet ist und ob er die entsprechenden Zugriffsberechtigungen hat, also Schüler bzw. Sozialpädagoge. So kann ein Schüler keinen Besuch bestätigen und ein Sozialpädagoge kann sich nicht austragen. Sind diese Bedingungen nicht erfüllt, so wird der Benutzer ausgeloggt und auf die Anmeldeseite umgelenkt.

##### Besuchsankündigungen

Als nächstes haben wir Besuchsankündigungen digitalisiert. Für diesen Prozess mussten wir eine weitere Entität hinzufügen:

```
Gast(id, name, zeitraum, bestaetigt, antrag, aktuell, ↑schueler_uid)
```

Ein Schüler meldet unter einem neuen Menüpunkt "Gast anmelden“ _(gast.php)_ einen Gast an, indem er ein Formular ausfüllt, in welchem der Name des Besuchers und der Zeitraum des Besuches angegeben werden müssen. In der Variable "antrag“ wird die Zeit der Stellung in Form eines DateTime-Objektes gespeichert, damit Missverständnisse durch Formulierungen wie "Morgen 12-14 Uhr“ als Zeitangabe vermieden werden können. Nach Abschicken des Formulars wird man auf die Startseite umgelenkt, wo ein Banner auftaucht, der die erfolgreiche Abschickung der Besuchsankündigung bestätigt. Zudem taucht ein zweites Banner auf, der einen informiert, dass der Besuch noch nicht von einem Sozialpädagogen bestätigt wurde.

Der Antrag wird dann den Sozialpädagogen unter dem Punkt "Besuchsankündigungen“ _(besuch.php)_ angezeigt. Dort können sie mithilfe eines Buttons die Ankündigung bestätigen, was dem entsprechenden Schülerauf der Seite "Besuchsankündigungen" mit seinen ausstehenden Besuchsankündigungen _(gaeste.php)_ angezeigt wird. Dort kann auch die Aktualität der Besuchsankündigungen ändern, indem man die Ankündigung mit dem kleinen Kreuz wegklickt, damit veraltete Bushcsankündigungen nicht ewig angezeigt werden.

##### Postdienst

Nachdem wir diese Hürde der Bürokratie also erfolgreich überwunden hatten, machten wir uns an den nächsten Dorn in unseren Augen. Am Internat ist es so geregelt, dass einige Mitglieder der Schloss-SG "Postdienst“ haben. Diese holen die Pakete, die im Sekretariat liegen, ab, und nehmen diese mit in ihre WG. Dann schreiben sie in die WhatsApp-Gruppe der Schülerschaft eine Nachricht, in der steht, für wen Pakete gekommen sind und wo sich diese befinden. Das Problem hierbei ist, das diese Nachrichten häufig übersehen werden, was dazu führt, das Pakete vergessen oder vermisst werden. Also haben wir uns überlegt, wie man diese Funktion in unser Projekt integrieren kann.

Für diesen Schritt haben wir das Boolean-Attribut "Postdienst“ zu der Schueler-Entität hinzufügen. Schüler, die Postdienst haben, können nun unter dem Stichpunkt "Neues Paket“ _(postdienst.php)_ eintragen, für wen ein Paket angekommen ist, und wo es ist. Die Angabe Orts ist nicht zwangsläufig auszufüllen, als Standardwert wird die WG des Postdiensthabenden abgegeben. Auch hier gibt es ein Auto-Complete, welches Schülernamen vervollständigt, um dem Postdienst die Arbeit zu erleichtern und Probleme durch Tippfehler bei Namen zu vermeiden, welches jedoch die gleiche Fehlfunktion wie oben beschrieben aufweist. Vergisst der Postdienst gänzlich einen Namen einzutragen, so wird die Umrandung dieses Feldes rot makiert, um darauf aufmerksam zu machen, dies wird im verarbeitenden Skript _(processing/paketep.php)_ festgelegt. 

Hat der Postdienst ein Paket eigetragen, wird ihm zwar wie üblich per Banner mitgeteilt, dass dieses erfolgreich war, jedoch wird er nicht auf die Startseite umgelenkt, da üblicherweise mehrere Pakete auf einmal eingetragen werden.

Wenn ein anderer Schüler sich nun einloggt sieht er oben in der Menüleiste, ob, und wie viele neue Pakete er hat. Dies wird durch einen Indikator, der neben dem Punkt "Pakete“ zu finden ist, angezeigt. Wenn ein Schüler ein Paket erhalten hat kann er dann auf den Menüpunkt klicken, wodurch er auf eine neue Seite umgelenkt wird, auf der die neuen Pakete einzeln in kleinen Boxen zu finden sind _(pakete.php)_. Hier wird auch angezeigt, wo sie zu finden sind. Hat der Schüler die Pakete nun abgeholt, so kann er auch die Aktualität verändern, sodass das Paket nicht mehr angezeigt wird _(processing/pakete-aktuell.php)_.

Im Verlaufe des Projekts ist uns aufgefallen, dass es Probleme geben könnte, sollten sich die Pakete bewegen oder der Postdienst sich bei der Eingabe vertippen. Deshalb haben wir für den Postdienst die Möglichkeit "Paket ändern“ _(post-bearbeitung.php)_ eingebaut. Auf dieser Seite werden alle aktuellen Pakete, wieder in einzelnen Boxen, angezeigt. Durch einen Klick auf den Stift in der rechten, oberen Ecke eines Kästchens kann man deren Inhalt ändern _(pakete-bearbeitung.php)_. Fehlangaben können auch gänzlich gelöscht werden.

##### Mängel und Defekte

Ein weiteres wichtiges Formular, welches das Internat betrifft, ist das, mit dem man Mängel und Defekte melden kann. Hiefür gibt es unter dem gleichnamigen Menüpunkt ein Formular, in dem man eintragen muss, wo genau der Schaden entstanden ist, was der Schaden ist und wahlweise zusätzlich den Unfallhergang angeben kann _(defekte.php)_. Füllt man eines der Pflichtfelder nicht aus, so kann das Formular nicht abgeschickt werden. Die Umrandung des entsprechenden Feldes wird rot gefärbt, damit man auf das Fehlen von Angaben aufmerksam gemacht wird _(processing/pakete-bearbeitung.php)_.

Das ausgefüllte Formular wird dann per E-Mail an die Hausmeister weitergeschickt. Hierfür werden Name und WG des ausfüllenden Schülers zusätzlich gespeichert, für den Fall dass es zu Nachfragen oder Konflikten kommt. Zum erfolgreichen Abschicken der E-Mail müsste man nun lediglich noch eine E-Mail-Adresse für das Austragebuch generieren, über die dann E-Mails verschickt werden können.

Auch hier wird man nach erfolgreichem Melden des Defekts auf die Startseite umgeleitet und mit einem Banner darüber informiert, dass die E-Mail abgeschickt wurde.

#### Der Telegram-Bot

Den Telegram-Bot haben wir mithilfe einer Open-Source-API zur Erstellung von Telegram-Bots, die auf GitHub, sowie auf der offiziellen Telegram-Webseite zu finden ist, implementiert. 

Diese funktionert, indem zunächst durch die Klasse `Main` der `Bot` gestartet wird. Updates, also zum Beispiel empfangene Nachrichten, in der Hauptklasse `Bot` die Methode `onUpdateReceived(Update update)` aufrufen. In dieser wird in einer Folge von if-Abfragen die passende Reaktione des Telegram-Bots ermittelt. Selbst hunzugefügte Methoden übernehmen dabei auch den Parameter _update_, um auf den betreffenden Nutzer und die Nachricht zugreifen zu können.

Grundsätzlich ist noch zu sagen, dass wir über die Klasse `Emoji` eine Implementierung von Emojis vorgenommen haben, um den Bot benutzerfreundlicher zu machen.

##### Grundfunktionen / Konversationsbeginn

Nachdem wir alle Features eingefügt hatten, beschlossen wir, einen Telegram-Bot zu programieren. Um den Austragebot nutzen zu können, muss ein Schüler zuerst seine Telegram-ID in die Datenbank eintragen, indem er sie unter dem Punkt "Telegram", der im Dropdownmenü unter Account verfügbar ist _(telegram.php)_, einträgt. Will er die Funktion danach wieder außer Betrieb setzen, so kann er das entsprechende Formular schlichtweg leer abschicken _(processing/telegram.php)_, wodurch die entsprechende Variable in der Datenbank wieder leer ist. Hierfür war es demnach nötig, dass wir ein weiteres Attribut für Schüler einfügen.

Weiß man seine Telegram-ID nicht, so muss man den Bot nur anschreiben. Dieser macht einen, sofern die genutzte Telegram-ID nicht angemeldet ist, dann darauf aufmerksam, dass man nicht in der Datenbank eingetragen ist, erklärt wie man dies ändern kann, und nennt einem seine Telegram-ID. Diese Anleitung erfolgt in der Methode `id(Update update)`

##### Hilfestellungen & Fehlerbehebung

Für weitere Nachfragen werden hier Ansprechpartner genannt, die sich mit den Vorgängen auskennen. Hierfür werden automatisch durch Telegram die Telegram-Konten verknüpft, damit man über den Link des Namens sofort auf einen Kontakt umgeleitet wird, der einem dann behilflich sein kann.

Grundsätzlich beginnt der Telegrambot eine neu begonnene Konversation mit einer Nachricht, die grundlegende Funktionen des Telegrambots erklärt und über die Möglichkeit, jederzeit nach Hilfe in Form einer standartisierten Informationsnachricht zu fragen oder einen Prozess zu beenden, informiert (s. onUpdateReceived/if-Bedingung "start"). 

 Zur Nutzerfreundlichkeit ist es zudem möglich, direkt über den Telegrambot mit dem Schlüsselwort "helpme" Nachrichten an Zuständige weiterleiten zu lassen, indem die Methode  `helpme(Update update)` aufgerufen wird. Umgekehrt kann eine Zuständiger durch den Telegrambot auf die Nachricht antworten, was über die Methode `checkGod(Update update)`erfolgt. 

Um letztlich alle Fehlfunktionen des Bots zu erfassen werden automatisch Nachrichten an Zuständige geschickt, wenn etwas nicht funktioniert hat, also zum Beispiel Exceptions aufgetreten sind. Diese Nachrichten als Objekte der Klasse `ErrMessage` enthalten alle wichtigen Information zum Fehler. Um Exceptions besser differenzieren zu könne, haben wir zudem die Klasse `CustomException` implementiert, die verschiedene Fehlertypen unterscheidet und in der Hauptklasse `Bot` Anwendung findet.

##### Austragen & Eintragen

Die Funktionalität des Bots, genauer gesagt die Anbindung an die Datenbank, erfolgt wie auch der Webservice, über php-Skripte, die sich alle um Unterordner _bot_ befinden. Die Anbindung an diese Skripte erfolgt über die Klasse `Connector` , in der sich (in der JavaDoc beschriebene) Methoden finden, die PHP-Skripte aufrufen.

Man kann nun also beim Bot über in der API implementierte Custom-Keyboards vorbereitete Optionen auswählen. Die CustomKeyboards finden sich hier in der Klasse `Keyboards`. Das Haupt-Keyboard ermöglicht es, die Optionen "Austragen" und "Zurücktragen" auswählen. Versucht man, sich auszutragen, obwohl man bereits ausgetragen ist, so wird man darauf hingewiesen, dass man bereits ausgetragen ist. Will man sich zurücktragen, obwohl man nicht ausgetragen ist, so wird man auch hier darauf hingewiesen. Diese Überprüfung erfolgt in den Methoden `checkAustragen(Update update)` und `checkZurücktragen(Update update)`.

Für das Austragen sind wieder dieselben Angaben wie bereits oben beschrieben nötig. Der Bot fragt diese nacheinander in einer Reihe von Methoden (`austragen1(Update update)`, `austragen2(Update update)`) ab, wobei der aktuelle Stand des Austrageprozesses in der HashMap _austragen_ gespeichert wird, und speichert die Informationen, die er aus den darauf antwortenden Textnachrichten erhält mithilfe der Methode `austragen(Update update)` in der Datenbank _(austragen.php)_. Auch hier werden einem beim Zurücktragen im ersten Schritt (`zurücktragen1(Update update)`) die zuletzt eigetragenen Daten genannt _(geteintrag.php)_, damit man überprüfen kann, ob der Eintrag korrekt ist, danach kann der Schüler mit der Methode `zurücktragen` zurückgetragen werden.

Da wir die Zeit der Rückkehr als DateTime-Objekt gespeichert haben, ist es wichtig, dass die Datenbank alle Eingaben einheitlich speichern kann, wozu sie die Eingaben erkennen muss, was durch eine Konversion durch ein php-Skript funktioniert _(dateFromString.php)_, welches in der `Connector`-Klasse mit der Methode `dateFromString(String back)` aufgerufen wird. Ist die Eingabe für den Bot nicht verständlich, so schickt er eine Nachricht, in der er dies kundtut und einem die Möglichkeit anbietet über den Befehl /formate mit der Methode `formate(Update update)` eine Auflistung aller erkennbaren Formate auszugeben. Hierbei wird die Formulierung einheitlich erklärt, sodass das korrekte Benutzen gewährleistet werden kann.

##### Erinnerungen

Zusätzlich schickt der Bot eine Erinnerung zur Zeit der angegebenen Rückkehr um die akkurate Erfassung der Schüler auf dem Schulgelände zu gewährleisten. Diese Erinnerungen sind anfangs automatisch eingeschaltet, können aber unter der Option "Einstellungen" ausgeschaltet werden.

Wir haben uns dazu entschieden, diese zu implementieren, um das typische Problem, dass ein Schüler vergisst, sich zurückzutragen, zu umgehen. Ein Vergessen wird zurzeit mit einem Strich geahndet, wobei zwei Striche S1 bedeuten, wodurch diese Vergesslichkeit umso ärgerlicher ist.

Die Erinnerungen werden mithilfe der Methode `erinnerung(Integer chatId, String back)` für den angegebenen Zeitpunkt erstellt.

##### Postdienst

Die zweite Option, die man über den Telegram-Bot nutzen kann, ist die des Postdienstes, die unter dem Punkt "Pakete" zu finden ist. Jemand, der Postdienst hat _(is-postdienst.php)_, kann hier mit der Methode `neuesPaket(Update update)` Pakete anmelden, indem er die Option "Neues Paket registrieren" wählt _(neues-paket.php)_. Wieder werden die Daten durch Textabfragen erfasst. Die Differenzierung und die Optionen zum Postdienst erfolgen dabei in der Methode `pakete(Update update)`.

Alle können mithilfe der Auswahl "Pakete einsehen" aufrufen, welche Pakete sie aktuell bekommen haben _(pakete.php)_, die erfolgt mithilfe der Methode `meinePakete(Update update)`. Wenn man die Pakete dann abgeholt hat, kann man die Aktualität mithilfe der Paket-ID auf false setzen _(paket-aktuell.php)_. Man muss diese nur anklicken, dann wird die Datenbank aktualisiert. Die ID steht immer hinter dem entsprechenden Paket, damit gut erkennbar ist, welche ID zu welchem Paket gehört. Dieser Vorgang wird auch in einer zusätzlichen Nachricht erklärt, um die Benutzerfreundlichkeit zu gewährleisten.

Da man durch den Telegram-Bot keine direkte Anzeige hat, wenn ein Paket gekomen ist, so wie es auf der Webseite ist, haben wir uns dazu entschlossen, einmal am Tag eine automatische Nachricht zu verschicken, wenn jemand noch aktuelle Pakete hat _(paketeNotification.php)_. Hierdurch ist auch die Eventualität umgangen, dass man unerwartet ein Paket bekommt und dieses nicht abholt, da man sich dessen nicht bewusst ist. Die Pakete-Notifications werden dabei durch die Methode `paketeNotification()` im Bot initiiert.

## Probleme

Insgesamt hatten wir drei nennenswerte Probleme bei der Programmierung.

##### 1. Problem: Auto-Complete

Die Funktion der automatischen Ergänzung von Schülernamen, die sowohl bei der Zurücksetzung von Passwörtern als auch beim Eintragen von Paketen relevant ist, hat uns am Anfang einige Kopfschmerzen bereitet.

Wir hatten hierfür Code-Beispiele aus dem Internet herausgesucht, welche auf Auto-Complete ausgelegt waren. Da dies nicht auf Anhieb geklappt hat, der Code an sich uns aber durchaus sinnvoll vorkam, haben wir weiterrecherchiert. Häufig kam es leider auch vor, dass komplett aus dem Internet kopierter Code in unserer Anwendung schlicht nicht funktioniert hat.

###### Lösung

Dieses Problem ließ sich schließlich dadurch lösen, dass wir uns mit mehreren unterschiedlichen Quellen intensiv auseinandergesetzt haben und somit den Auto-Complete in sich und die Verbindung zur Datenbank aus unterschiedlichen Vorlagen zusammenschneiden konnten. Wie oben erwähnt, scheint die Auto-Complete-Funktion dennoch nicht überall zu funktioneren, wofür wir leider keinen Lösungsansatz finden konnten.

##### 2. Problem: Bot - Verbindung zur Datenbank

Nachdem wir uns mit Bots allgemein außeinandergesetzt hatten, kam nun die Frage auf, wie man hierbei die Verbindung zu einer Datenbank herstellt, sodass der Bot direkt Einträge vornehmen kann.

Da die Telegram-Bot in Java für uns am einfachsten und benutzerfreundlichsten wirkte, kam zuerst also die Idee auf, die gesamte Implementierung in Java vorzunehmen, also auch MySQL dort aufzurufen. Die zweite Möglichkeit war den Telegram-Bot in PHP zu schreiben, wo wiederrum MySQL in unserer bisherigen Programmierung seine Anwendung findet, wenn man man mit Webseiten arbeitet, die auf Datenbanken zugreifen. 

Das Problem war dabei, dass wir weder die PHP-Telegram-API, noch die Anbindung von MySQL in Java funktionsfähig bekommen konnten.

###### Lösung

Schließlich haben wir uns dafür entschlossen beides zu kombinieren. Das Grundgerüst für den Bot haben wir also in Java geschrieben, die Verbindung zu der Datenbank in PHP. Der Bot greift nun auf die Datenbank zu, indem im Java-Code php-Skripte aufgerufen werden.

##### 3. Problem: Bot - Gleichzeitige Anfragen

Dieses Problem entsteht, wenn beispielsweise zwei Schüler gleichzeitig versuchen, sich auszutragen. Dadurch, dass alle Anfragen von einem Programm bearbeitet werden, werden Variablen, in denen Informationen für den Austrageprozess zwischengespeichert werden, überschrieben, sollten zwei Schüler diese Variable zur selben Zeit definieren.

Dadurch könnte es also zu Fehleinträgen in der Datenbank kommen, ohne dass der Benutzer es bemerkt, was nicht erwünscht ist.

###### Lösung

Wir brauchten demnach die Möglichkeit, mehrere Variablen gleichzeitig speichern zu können. Hierfür haben wir Hashmaps für die betroffenen Variablen angelegt. Der Schlüssel ist hierfür die Telegram-ID des Schülers. Dadurch, dass diese einzigartig ist, kann man sie mit der Datenbank abgleichen, in der die Telegram-IDs der Schüler gespeichert sind, sodass die Daten, die den jeweiligen Schlüsseln zugeordnet sind, beim richtigen Schüler eingetragen werden können.

## Arbeitsteilung

Zur Arbeitsteilung in diesem Projekt lässt sich allgmein sagen, dass zunächst Linda das Grundgerüst für die Seite aufgebaut hat, wir dieses anschließend besprochen haben, und dann damit fortgefahren sind die einzelnen ergänzenden Seiten gemeinsam auszubauen und neue Features hinzuzufügen.

Wir haben also an vielen Punkten gemeinsam gearbeitet, besonders beim Telegram-Bot, wo die Aufteilung in PHP-Skripte und Java-Methoden erfolgt ist; dabei hat Jule einige der PHP-Seiten edititert, während Linda diese dann in die Java-Anbindung eingefügt hat.

## Lernerfolg

Insgesamt haben wir viel über die Anwendung von Datenbanken gelernt, wie das Aufrufen von MySQL-Befehlen in PHP oder die Benutzung von Session-Variablen, um Nutzersysteme zu implementieren. Auch die Verbindung von PHP und Java war neu für uns, ebenso wie die Verwendung von Bootstrap.

Eine weitere Neuheit war die Form der Zusammenarbeit insbesondere in Zusammenhang mit der Programmierung. Hierbei war das Zeitmanagement auch wichtig, damit bestimmte Seiten in ihrer Vollständigkeit ausgetestet werden konnten, sodass jegliche eventuellen Probleme, die bei der Ausführung auftreten könnten, noch bemerkt werden würden, während man noch mit der Entwicklung des Codes beschäftigt ist und die Fehlerbehebung intuitiver ist.

Generell war es auch interessant, den Aufbau unterschiedlicher Seiten zu betrachten, da man hier durch Anpassung bereits verhandener Seiten eine Menge Arbeit sparen kann. So ist das Endprodukt auch übersichtlicher; Abänderungen, die für mehrere Seiten gewünscht sind, sowie Fehlerbehebung werden vereinfacht.

Allgemein lässt sich auch sagen, dass bei einem so großen Projekt Effizienz sehr wichtig ist, da es sonst sehr schnell nicht mehr überschaubar ist und man deshalb an vielen Stellen unterschiedliche Dinge ausprobiert, damit alles möglichst reibungslos abläuft.
