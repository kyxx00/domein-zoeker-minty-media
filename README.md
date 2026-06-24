Domein Zoeker – Minty Media Stage Opdracht

Wat heb je nodig?

- XAMPP (of WAMP / MAMP)
- PHP 7.4 of hoger
- MySQL



Installatie stappen

Bestanden neerzetten
Zet de map "domein-zoeker-minty-media" in je htdocs map van XAMPP.

2. Database aanmaken
Open phpMyAdmin en voer het bestand "database.sql" uit.
Dat maakt automatisch de database en de tabellen aan.

3. Database gegevens aanpassen (als nodig)
Open includes/db.php en pas de gegevens aan:
php
$host = "localhost";
$dbname = "domein_zoeker";
$user = "root";
$pass = "";


4. Opstarten
Ga naar: http://localhost/domein-zoeker-minty-media/



Hoe werkt het?

1. Zoeken – Typ een domeinnaam en klik op Zoeken. Je ziet 12 extensies met prijs en beschikbaarheid.
2. Winkelmand – Klik op "+ Toevoegen" bij een beschikbaar domein. Niet beschikbare domeinen kun je niet toevoegen.
3. Checkout – Vul je naam en e-mail in en plaats je bestelling. Je ziet subtotaal + BTW (21%).
4. Bestellingen – Alle geplaatste bestellingen staan hier met alle details.


Projectstructuur

domein-zoeker/
│
├── index.php                Zoekpagina
├── database.sql             SQL
│
├── includes/
│   ├── db.php               Database verbinding
│   └── api.php              API connectie
│
├── pages/
│   ├── winkelmand.php       Winkelmand pagina
│   ├── checkout.php         Checkout en bestelling opslaan
│   └── bestellingen.php     Overzicht van alle bestellingen
│
└── assets/
    └── css/
        └── style.css        Styling
