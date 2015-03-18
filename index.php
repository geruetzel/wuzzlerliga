<!DOCTYPE html>
<html>
<head lang="de">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="favicon/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.js"></script>
    <link rel="stylesheet" type="text/css" href="style/tablestyle/tablestyle.css" />
    <meta charset="UTF-8" />
    <title>Wuzzlerliga</title>

    <!-- Tabelle sortierbar machen, Spaltennr. 1-9 nicht sortierbar -->
    <script type="text/javascript">
        $(document).ready(function()
            {
                $("#ranking").tablesorter({widgets: ['zebra']});
                $("#games").tablesorter({widgets: ['zebra'],headers:{1:{sorter:false},2:{sorter:false},3:{sorter:false},4:{sorter:false},5:{sorter:false},6:{sorter:false},7:{sorter:false},8:{sorter:false},9:{sorter:false},10:{sorter:false}}});
            }
        )
    </script>

</head>
<body>

<?php
include "functions.inc.php";
?>

<h2>easyname & Nessus Wuzzlerliga</h2>

<!--
<h3>Die Saison 2015 hat begonnen!</h3>
    <pre>
        Die Statistiken wurden zurückgesetzt - neue Saison - neues Glück!

        Neuerung:
        Sätze werden nun bis 5 Tore gespielt, es seidenn, es steht 1 zu 1 in Sätzen,
        dann wird im dritten Satz auf eine Tordifferenz von 2 gespielt - ansonsten gewinnt derjenige, der als
        Erstes 8 Tore geschossen hat.
    </pre>
-->
<h3>Aktuelles Ranking</h3>

<table id="ranking" class="tablesorter">
    <thead>
    <tr>
        <th>Spieler</th>
        <th>ELO</th>
        <th>Spiele gewonnen</th>
        <th>Spiele gespielt</th>
        <th>Winrate</th>
        <th>S&auml;tze gespielt</th>
        <th>S&auml;tze gewonnen</th>
        <th>Trankln</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $con = mysqli_connect("localhost", "wuzzler", "wuzzlernoob");
    mysqli_select_db($con, "wuzzeln");

    $players = getPlayerNames();
    $num_players = count(getPlayerNames());

    // Table stats befüllen
    for($i=0; $i<$num_players; $i++) {
        $query = "insert into stats (player, winrate, elo, games_played, games_won, sets_played, sets_won, zunulls) values " . "('" .
            $players[$i] . "', '" .
            getWinRate($players[$i]) . "' ,'" .
            getPlayerElo($players[$i]) . "' ,'" .
            getPlayerGames($players[$i]) . "' ,'" .
            getPlayerWins($players[$i]) . "' ,'" .
            getPlayerSets($players[$i]) . "' ,'" .
            getPlayerWins($players[$i]) * 2 . "' ,'" . // da der Gewinner immer 2 Sätze gewonnen hat, ist die Anzahl der gewonnenen Sätze gleich PlayerWins * 2
            getZuNulls($players[$i]) . "')";
        mysqli_query($con, $query) or die(mysqli_error($con));
    }

    // Table stats auslesen und in Tabelle ausgeben
    $query = "SELECT * FROM stats ORDER BY elo DESC";
    $result = mysqli_query($con, $query);

    while ($dsatz = mysqli_fetch_assoc($result))
    {
        echo "<tr>";
        echo "<td>" . $dsatz['player'] . "</td>";
        echo "<td align='right'>" . round($dsatz['elo']) . "</td>";
        echo "<td align='right'>" . $dsatz['games_won'] . "</td>";
        echo "<td align='right'>" . $dsatz['games_played'] . "</td>";
        echo "<td align='right'>" . number_format($dsatz['winrate'], 2, ',', ' ') . " %</td>";
        echo "<td align='right'>" . $dsatz['sets_played'] . "</td>";
        echo "<td align='right'>" . $dsatz['sets_won'] . "</td>";
        echo "<td align='right'>" . $dsatz['zunulls'] . "</td>";
        echo "</tr>";
    }

    // nach Ausgabe die Table stats leeren
    mysqli_query($con, "TRUNCATE stats");

    ?>
    </tbody>
</table>

<h3>Bisherige Ergebnisse</h3>

<table id="games" class="tablesorter"> <!-- Tabelle Spielanzeige -->
    <thead>
    <tr>
        <th>Nr.</th>
        <th>Datum</th>
        <th>Spieler 1</th>
        <th>Spieler 2</th>
        <th>Punkte Spieler 1</th>
        <th>Punkte Spieler 2</th>
        <th>ELO Spieler 1</th>
        <th>ELO Spieler 2</th>
        <th>Zu Null Satz 1</th>
        <th>Zu Null Satz 2</th>
        <th>Zu Null Satz 3</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // Query für Spieleliste
    $result = mysqli_query($con, "select * from games order by id desc");
    $num = mysqli_num_rows($result);

    // Datensätze aus Ergebnis ermitteln, in Array speichern und ausgeben
    while($dsatz = mysqli_fetch_assoc($result))
    {
        // Die ELO-Differenz für jeden Spieler
        $diff_pl1_un = $dsatz["elo_player1"] - getLastEloFromCertainGame($dsatz["player1"], $dsatz["id"]);
        $diff_pl2_un = $dsatz["elo_player2"] - getLastEloFromCertainGame($dsatz["player2"], $dsatz["id"]);
        // ELO-Differenz runden
        $diff_pl1 = round($diff_pl1_un);
        $diff_pl2 = round($diff_pl2_un);

        // Wenn die ELO-Differenz ein Punktezuwachs ist, ein Plus voranstellen, ansonsten nichts, da das Minus ohnehin angezeigt wird
        if ($diff_pl1 > 0)
            $vorz1 = "+";
        else
            $vorz1 ="";

        if ($diff_pl2 > 0)
            $vorz2 = "+";
        else
            $vorz2 ="";

        /* Bei Saisonswechsel sollte hier abgefragt werden, ob das Spiel das erste für den Spieler in der
         Saison ist, falls ja soll keine Differenz angezeigt werden oder sogar die Differenz zu seinem letzten Spiel in der vorigen Saison */


        // Tabelle Reihe für Reihe ausgeben
        echo "<tr>";
        echo "<td>" . $dsatz["id"] . "</td>";
        echo "<td>" . $dsatz["date"] . "</td>";
        echo "<td>" . $dsatz["player1"] . "</td>";
        echo "<td>" . $dsatz["player2"] . "</td>";
        echo "<td>" . $dsatz["score_player1"] . "</td>";
        echo "<td>" . $dsatz["score_player2"] . "</td>";
        echo "<td>" . round($dsatz["elo_player1"]) . " (" . $vorz1 . $diff_pl1 . ")</td>";
        echo "<td>" . round($dsatz["elo_player2"]) . " (" . $vorz2 . $diff_pl2 . ")</td>";
        echo "<td>" . $dsatz["ace_set1"] . "</td>";
        echo "<td>" . $dsatz["ace_set2"] . "</td>";
        echo "<td>" . $dsatz["ace_set3"] . "</td>";
        echo "</tr>";

    }
    ?>
    </tbody>
</table> <!-- Ende der Tabelle für Anzeige der Spiele -->



</body>
</html>