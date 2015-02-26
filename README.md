# wuzzlerliga
Tischfußball Ligasystem v.0.000002
by geruetzel - geruetzel@gmail.com

+++INSTALLATION+++++++++++++++++++
1. .htpassword erstellen für die existierende .htaccess um Zugriff aufs Verzeichnis /admin/ zu schützen
2. MYSQL Datenbanktemplate "wuzzeln.sql" in neue Datenbank importieren, anschließend löschen
3. MYSQL Zugangsdaten in "functions.inc.php" anpassen (in function dbConnect())

+++VERWENDUNG+++++++++++++++++++++
- Frontend für alle User ist über die index.php im Rootverzeichnis erreichbar
- Adminzugang über /admin/ (login per httpauth)

