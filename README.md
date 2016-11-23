# miss_ict

Prototyp zur übersichtlichen Darstellung der ICT-Module im Rahmen der EFZ-Ausbildung Informatiker (API & SYS). Als solches sind die gelieferten Daten insbesondere für Fachschaften ausserhalb DB, API und WEB oft unvollständig.

Fehlende (WISS)-LBVs können mit Modulnummer an schirmer@green-orca.com gesendet werden.

Die Darstellung folgt strikt dem Bauhaus-Prinzip: Form follows function. LBVs und Toolboxen sind von Word in HTML re-konvertiert, damit die Archivierbarkeit und Wartbarkeit sichergestellt ist.

##Benutzung 
**Anmeldung**
user, 0815AlphaBetaGagga!

Module auf der linken Seite sowie LBV und HANOKs auf der rechten Seite sind klickbar.
Webserver-Applikation zur übersichtlichen Darstellung der ICT Module in der Informatiker-Grundausbildung (EFZ) in der Schweiz.

Zu jedem Modul werden Nummer, Titel, Fachbereich, Semester *(schwierig wegen kantonaler Unterschiede)*, Fachrichtung (API,SYS oder leer für beides) angezeigt. Per Mausklick werden die entsprechenden vom ICT zentral vorgegebenen HANOKS geladen. Soweit vorhanden, können vorgängig benötigte Module, die aktuelle LBV und teilweise auch die Toolbox als HTML angeschaut werden. Einzelne HANOKs sind per Mausklick ausklappbar.

##Installation
- Apache2x/PHP5.6+/MySQL5.5+
- Datenbankskript liegt im DB Ordner (CREATE DB hinzufügen)
- Inhalt des WWW Ordners in entsprechenden Webspace kopieren bzw. Apache entprechend konfigurieren
- miss_ict.ini.php an eigene mysql - Benutzername, password, Datenbank anpassen

##Technische Verbesserungsmöglichkeiten
Die Anwendung ist zunächst aus reinem Eigennutz entstanden und entsprechend insbesondere sicherheitstechnisch verbesserungswürdig.

- *PRIO1:* ajax.php sollte bestehendes wiss_db Objekt weiterverwenden (connection sharing)
- ordentliches MVC
- Zugriff auf statische Toolbox- und LBV- files 

*Modulinformationssystem Schirmer - ICT*
