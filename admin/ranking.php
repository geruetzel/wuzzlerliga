<!DOCTYPE html>
<html>
<head lang="de">
    <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
    <link rel="stylesheet" type="text/css" href="../style/tablestyle/tablestyle.css" />
    <meta charset="UTF-8" />
    <title>Ranking</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">


    <!-- Tabelle sortierbar machen mit Tablesorter -->
    <script type="text/javascript">
        $(document).ready(function()
            {
                $("#ranking").tablesorter({widgets: ['zebra']});
            }
        );
    </script>

</head>
<body>

<?php
    include "../functions.inc.php";
?>

    <h2>Ranking</h2>

<?php include "menu.php"; ?>


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


</body>
</html>