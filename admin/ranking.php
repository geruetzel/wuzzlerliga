<!DOCTYPE html>
<html>
<head lang="de">
    <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
    <link rel="stylesheet" type="text/css" href="../style/tablestyle/tablestyle.css" />
    <meta charset="UTF-8" />
    <title>Ranking</title>

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