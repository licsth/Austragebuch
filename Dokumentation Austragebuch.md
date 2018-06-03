# Dokumentation Austragebuch

Das digitale Austragebuch ist ein Vorschlag als Alternative zum aktuellen Aus- tragebuch in Papierform. Es soll es den Schülern erleichtern, sich vom Schul- und Internatsgelände auszutragen, indem wir eine Webseite erstellt haben, die über eine Anbindung an eine Datenbank Einträge der Schüler speichert und verwaltet und sie den SozPäds anzeigt. Darüber hinaus verfügt das digitale Austragebuch über ein System zur Verwaltung von Besuchsankündigungen und dem Postdienst.

Zusätzlich zu dem Webservice sind die Funktionen zum Austragebuch und Post- dienst ebenfalls über einen Telegram-Bot verfügbar.



### Table of contents

1. [Die Idee](#"die idee")

2. [Umsetzung](#umsetzung)



## Die Idee

Die Idee des Projektes war es, das Austragebuch, welches am Internat benutzt wird, um festzuhalten, wer das Gelände verlässt, wohin er geht und wann er wiederkommt, zu digitalisieren. Es sollten also alle Schüler einen Account haben, mit dem sie sich online einloggen können um sich auszutragen. Die Sozialpädagogen sollten zudem Zugriff auf die Daten erhalten, um beispielsweise in einem Brandfall die Anwesenheit korrekt feststellen zu können.

Später kam zu dieser Grundidee unsere Unzufriedenheit mit der Zettelwirtschaft, die im Internat für die meisten Prozesse betrieben wird. So gibt es unterschiedliche Formulare für die Anmeldung von Besuch oder um Mängel und Defekte zu melden. Diese können nun ebenfalls über die Datenbank ausgefüllt und verarbeitet werden.

Die nächste Idee resultierte aus der leichten Ineffizienz, die die momentane Regelung des Postdienstes mit sich bringt. Im Moment wird in der Theorie einmal am Tag in die WhatsApp-Gruppe der gesamten Schülerschaft geschrieben, für wen Pakete gekommen sind und wo sich diese im Moment befinden. Demnach haben wir auch den Postdienst im Austragebuch zusätzlich implementiert.



## Umsetzung

Zu Beginn mussten wir eine Struktur für die Datenbank festlegen, welche den Ansprüchen an die Funktionalitäten genügt. Angefangen hat die Umsetzung mit drei Entitäten:

```sql
Schueler(Name, Vorname, uid, passwort, wg),
Sozpaed(Name, Vorname, uid, passwort),
Eintrag(id, zeit, wohin, zurueck, absprache, ↑schueler_uid)
```

Es gibt also Accounts für Sozialpädagogen und Schüler. Schüler erstellen einen Eintrag, wenn sie das Gelände verlassen. Hierfür nimmt die Datenbank automatisch einen Zeitstempel von der Zeit des Eintrages und speichert den Nutzernamen (uid) des Schülers, sodass dieser nur noch eingeben muss, wohin er geht, wann er zurück ist und wenn nötig, mit welchem Sozialpädagogen dies abgesprochen ist.

Im Verlaufe dessen haben wir die Umwandlung aller uns bekannten Eingabeformen implementiert, sodass diese in ein Zeitformat überführt werden kann, welches dann in der Datenbank gespeichert wird. Dies diente der Benutzbarkeit und intuitiven Bedienung, sowie der Effizienz im Vergleich zum papierbasierten Austragebuch, welches zurzeit verwendet wird. Hierfür haben wir uns entschieden, da ein DateTime Objekt gegenüber einer reinen Texteingabe viele Vorteile hat, da es zum Beispiel universeller ist und somit einfacher weiterverwendet und verstanden werden kann.

Hierfür sind demnach die einzelnen Accounts der Schüler gefordert, für die wir noch einige Informationen brauchen.

Die uid des Schülers setzt sich automatisch aus Vornamen und Nachnamen zusammen nach dem Schema vorname.nachname. Dies ist auch das Passwort, welches am Anfang generiert wird. Für die Sozialpädagogen wurde eine ähnliche Vorgehensweise benutzt. Hier setzt sich beides aus dem ersten Buchstaben des Vornamens und dem Nachnamen zusammen. Also: v.nachname, wie auch bei den offiziellen Hansenberg-E-Mail-Adressen.

An dieser Stelle haben wir auch die Möglichkeit, sein Passwort zu ändern, eingebaut. Hierfür gibt es eine Seite, die unter der Option Account in einem Dropdown-Menü zu öffnen ist. Dort steht ein Formular zu Verfügung, in das zur Kontrolle das alte und dann das neue Passwort eigegeben werden. Das neue Passwort wird dann in gehashter Form in der Datenbank eingetragen, um die Sicherheit der Passwörter zu garantieren. Danach erfolgt eine Umleitung auf die Startseite, auf der man durch ein Bootstrap-generiertes Banner darauf hingewiesen wird, dass die Änderung des Passwortes erfolgreich durchgeführt wurde. Hat man sein Passwort noch nicht geändert, so erscheint auf der Startseite ein Banner, das einen daran erinnert, dass man, um maximale Sicherheit zu gewährleisten, sein Passwort ändern sollte.

Grundsätzlich lässt sich sagen, dass wir auf allen Seiten - mit Ausnahme der Anmeldeseite – zuerst überprüfen, ob der Nutzer angemeldet ist und ob er die entsprechende Rolle hat. So kann ein Schüler keinen Besuch bestätigen und ein Sozialpädagoge kann sich nicht austragen. Sind diese Bedingungen nicht erfüllt, so wird der Benutzer ausgeloggt und auf die Anmeldeseite umgelenkt.

Als nächstes haben wir Besuchsankündigungen digitalisiert. Für diesen Prozess mussten wir eine weitere Entität hinzufügen:

```
Gast(id, Name, Zeitraum, bestätigt, antrag, aktuell, ↑schueler_uid)
```

Ein Schüler meldet unter einem neuen Menüpunkt („Gast anmelden“) einen Gast an, indem er ein Formular ausfüllt, in welchem der Name des Besuchers und der Zeitraum des Besuches angegeben werden müssen. In der Variable „antrag“ wird die Zeit der Stellung in Form eines DateTime Objektes gespeichert, damit Missverständnisse durch Formulierungen wie „Morgen 12-14“ als Zeitangabe vermieden werden können. Nach Abschicken des Formulars wird man auf die Startseite umgelenkt, wo ein Banner auftaucht, der die erfolgreiche Abschickung der Besuchsankündigung bestätigt. Zudem taucht ein zweites Banner auf, der einen informiert, dass der Besuch noch nicht von einem Sozialpädagogen bestätigt wurde.

Der Antrag wird dann den Sozialpädagogen unter dem Punkt „Besuchsankündigungen“ angezeigt. Dort können sie Mithilfe eines Buttons die Ankündigung bestätigen, was dem entsprechenden Schüler mithilfe eines Banners dann auf seiner Startseite angezeigt wird. Ebenfalls aufzurufen sind die Besuchsankündigungen mitsamt ihrem Status für den Schüler unter dem Menüpunkt „Besuchsankündigungen“. Dort kann auch die Aktualität der Besuchsankündigungen ändern, indem man die Ankündigung mit dem kleinen Kreuz wegklickt.

Nachdem wir diese Hürde der Bürokratie also erfolgreich überwunden hatten, machten wir uns an den nächsten Dorn in unseren Augen. Am Internat ist es so geregelt, dass einige Laute aus der Schloss-SG „Postdienst“ haben. Diese holen die Pakete, die im Sekretariat liegen ab und nehmen diese mit in ihre WG. Dann schreiben sie in die WhatsApp-Gruppe der gesamten Schülerschaft eine Nachricht in der steht für wen alles Pakete gekommen sind und wo sich diese befinden. Das Problem hierbei ist, das man zumeist die Nachrichten aus diesem Chat ignoriert, da viele irrelevant sind. Dadurch bekommt man manchmal nicht mit, wenn man ein Paket erhalten hat. Also haben wir uns überlegt, wie man diese Funktion in unser Projekt integrieren kann.

Für diesen Schrittmussten wir die Boolean-Variable „Postdienst“ zu Schueler hinzufügen. Schüler, welche Postdienst haben, können nun unter dem Stichpunkt „neues Paket“ eintragen, für wen ein Paket gekommen ist und wo es ist. Die Angabe wo es ist brauchen sie nicht auszufüllen, wenn es in ihrer eigenen WG ist. Lassen sie dieses Feld also leer wird es automatisch mit ihrer WG vervollständigt.
