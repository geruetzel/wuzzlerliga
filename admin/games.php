<!DOCTYPE html>
<html>
<head lang="de">
    <meta charset="UTF-8" />
    <title>Spielergebnisse</title>

        <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
        <link rel="stylesheet" type="text/css" href="../style/tablestyle/tablestyle.css" />

        <!-- Tabelle sortierbar machen, Spaltennr. 1-10 nicht sortierbar -->
        <script type="text/javascript">
            $(document).ready(function()
                {
                    $("#games").tablesorter({widgets: ['zebra'],headers:{1:{sorter:false},2:{sorter:false},3:{sorter:false},4:{sorter:false},5:{sorter:false},6:{sorter:false},7:{sorter:false},8:{sorter:false},9:{sorter:false},10:{sorter:false},11:{sorter:false}}});
                }
            );


            function checkForm()
            {
                var strFehler='';
                var score1 = parseInt(document.forms[0].score_player1.value);
                var score2 = parseInt(document.forms[0].score_player2.value);
                var sumOfSets = score1 + score2;

                if (document.forms[0].date.value=="")
                    strFehler += "Bitte ein Datum eingeben!\n";
                if (document.forms[0].player1.value=="" && document.forms[0].player2.value=="")
                    strFehler += "Bitte Spieler 1 und 2 auswählen!\n";
                if (document.forms[0].player1.value == document.forms[0].player2.value)
                    strFehler += "Spieler 1 darf nicht gleich Spieler 2 sein!\n";
                if (document.forms[0].player1.value =="")
                    strFehler += "Bitte Spieler 1 auswählen!\n";
                if (document.forms[0].player2.value =="")
                    strFehler += "Bitte Spieler 2 auswählen!\n";
                if (document.forms[0].score_player1.value =="")
                    strFehler += "Bitte Punkte für Spieler 1 angeben!\n";
                if (document.forms[0].score_player2.value =="")
                    strFehler += "Bitte Punkte für Spieler 2 angeben!\n";
                if (document.forms[0].score_player1.value == document.forms[0].score_player2.value)
                    strFehler += "Es muss einen eindeutigen Sieger geben!\n";
                if (sumOfSets < 2)
                    strFehler += "Die Anzahl der gespielten Sätze muss mindestens 2 sein!\n";
                if (sumOfSets > 3)
                    strFehler += "Die Anzahl der gespielten Sätze darf maximal 3 sein!\n";


                if (strFehler.length > 0)
                {
                    alert("Fehler: \n\n" + strFehler);
                    return (false);
                }
            }
        </script>


</head>
<body>

    <h2>Spielergebnisse</h2>

<?php

    include "../functions.inc.php";
    include "menu.php";
    include "../Rating.php";

    // Datenbankverbindung
    $con = dbConnect();

    // das aktuelle Datum in Variable speichern
    $heute = date("Y-m-d");
?>


    <h3>Neues Ergebnis eintragen</h3>
    <?php
        /* Formular zum Hinzufügen neuer Spielergebnisse */
        echo "
            <form action='games.php' method='post' onsubmit='return checkForm()'>
                <table>
                    <tr>
                        <td>Datum</td>
                        <td><input type='text' value='$heute' name='date' size='10' /></td>
                    </tr>
                    <tr>
                        <td>Spieler 1</td>
                        <td>
                            <select name='player1'>
                            <option></option>";

                            $players = getPlayerNames();

                            // Einfüllen der einzelnen Spielernamen in option Tags
                            for ($i=0; $i<count(getPlayerNames()); $i++)
                            {
                                echo "<option>" . $players[$i] . "</option>";
                            }
        echo "
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td>Spieler 2</td>
                        <td>
                            <select name='player2'>
                            <option></option>";

                            $players = getPlayerNames();

                            // Einfüllen der einzelnen Spielernamen in option Tags
                            for ($i=0; $i<count(getPlayerNames()); $i++)
                            {
                                echo "<option>" . $players[$i] . "</option>";
                            }
        echo "
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Punkte Spieler 1</td>
                        <td>
                            <select name='score_player1'>
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Punkte Spieler 2</td>
                        <td>
                            <select name='score_player2'>
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>

                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td>zu Null - 1. Satz</td>
                        <td>
                            <select name='ace_set1'>
                                <option></option>";

                                $players = getPlayerNames();

                                // Einfüllen der einzelnen Spielernamen in option Tags
                                for ($i=0; $i<count(getPlayerNames()); $i++)
                                {
                                    echo "<option>" . $players[$i] . "</option>";
                                }

        echo "              </select>
                        </td>
                    </tr>


                     <tr>
                        <td>zu Null - 2. Satz</td>
                        <td>
                            <select name='ace_set2'>
                                <option></option>";

                                // Einfüllen der einzelnen Spielernamen in option Tags
                                for ($i=0; $i<count(getPlayerNames()); $i++)
                                {
                                    echo "<option>" . $players[$i] . "</option>";
                                }

    echo "                  </select>
                        </td>
                    </tr>

                     <tr>
                        <td>zu Null - 3. Satz</td>
                        <td>
                            <select name='ace_set3'>
                                <option></option>";

                                // Einfüllen der einzelnen Spielernamen in option Tags
                                for ($i=0; $i<count(getPlayerNames()); $i++)
                                {
                                    echo "<option>" . $players[$i] . "</option>";
                                }

    echo "                  </select>
                        </td>
                    </tr>


                    <tr> <!-- Absenden- und Reset-Buttons-->
                        <td><input type='submit' name='sent' value='Spiel eintragen' /></td>
                        <td><input type='reset' /></td>
                    </tr>
                </table>
            </form>

            "; /* Ende Formular zum Hinzufügen neuer Spielergebnisse */
    ?>

    <!-- Tabelle für Anzeige der Spiele -->
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
                <th>Optionen</th>
            </tr>
        </thead>
        <tbody>
        <?php

            if(isset($_POST["sent"]))
            {
                $con = dbConnect();

                $id_last = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM games ORDER BY id DESC LIMIT 1"));
                $id_new = $id_last["id"] + 1;

                // wenn spieler 1 gewinnt
                if ($_POST["score_player1"] > $_POST["score_player2"])
                    $rating = new Rating(getPlayerElo($_POST["player1"]), getPlayerElo($_POST["player2"]), 1, 0);

                // wenn spieler 2 gewinnt
                if ($_POST["score_player1"] < $_POST["score_player2"])
                    $rating = new Rating(getPlayerElo($_POST["player1"]), getPlayerElo($_POST["player2"]), 0, 1);

                // Die neberechneten Elowerte in $results abspeichern
                $results = $rating->getNewRatings();

                // Neue ELO in Table players eintragen
                $query2 = "UPDATE players SET elo_current = '" . $results["a"] . "' WHERE name =" . "'" . $_POST['player1'] . "'";
                $query3 = "UPDATE players SET elo_current = '" . $results["b"] . "' WHERE name =" . "'" . $_POST['player2'] . "'";

                mysqli_query($con, $query2);
                mysqli_query($con, $query3);

                // Eingegebenes Ergebnis in die Table games eintragen
                $query = "insert into games (id, date, player1, player2, score_player1, score_player2, elo_player1, elo_player2, ace_set1, ace_set2, ace_set3) values"
                    . "('" . $id_new
                    . "', " . "'" . $_POST["date"]
                    . "', " . "'" . $_POST["player1"]
                    . "', " . "'" . $_POST["player2"]
                    . "', " . "'" . $_POST["score_player1"]
                    . "', " . "'" . $_POST["score_player2"]
                    . "', " . "'" . getPlayerElo($_POST["player1"])
                    . "', " . "'" . getPlayerElo($_POST["player2"])
                    . "', " . "'" . $_POST["ace_set1"]
                    . "', " . "'" . $_POST["ace_set2"]
                    . "', " . "'" . $_POST["ace_set3"] . "')";

                mysqli_query($con, $query);

                // Ergebnis an HipChat Wuzzlerchannel senden
                $hip = " ++++++++++ NEUES SPIELERGEBNIS: " . $_POST["player1"] . " vs. " . $_POST["player2"] . " " . $_POST["score_player1"] . ":" . $_POST["score_player2"] . " ++++++++++";

                postToHipChat($hip, "green");

            }

            // Einträge löschen
            if (isset($_POST["delete"]) && isset($_POST["id"]))
            {

                $id = $_POST["id"];

                // Das später zu löschende Spiel in Array speichern
                $data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM games WHERE id='$id'"));

                // Die letzte Elo der beiden Spieler in die elo_current schreiben
                $query2 = "UPDATE players SET elo_current = '" . getLastElo($data["player1"]) . "' WHERE name =" . "'" . $data["player1"] . "'";
                $query3 = "UPDATE players SET elo_current = '" . getLastElo($data["player2"]) . "' WHERE name =" . "'" . $data["player2"] . "'";

                mysqli_query($con, $query2);
                mysqli_query($con, $query3);

                // Das Spiel löschen
                $query = "DELETE FROM games WHERE id='$id'";

                if (!mysqli_query($con, $query))
                    echo "L&ouml;schen fehlgeschlagen: $query<br />" . mysql_error() . "<br /><br />";

                postToHipChat("++++++++++ Das letzte Spielergebnis wurde gelöscht ++++++++++", "red");

            }

            // Query für Spieleliste
            $result = mysqli_query($con, "select * from games order by id desc");
            $num = mysqli_num_rows($result);

            // Datensätze aus Ergebnis ermitteln, in Array speichern und ausgeben
            while($dsatz = mysqli_fetch_assoc($result))
            {
                // Die ELO-Differenz für jedes Spiel
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

                // Zur einfacheren Ausgabe in Tabelle die Differenz in einen String schreiben

                $elodiff_pl1 = $vorz1 . $diff_pl1;
                $elodiff_pl2 = $vorz2 . $diff_pl2;

                /* Bei Saisonswechsel sollte hier abgefragt werden, ob das Spiel das erste für den Spieler in der
           Saison ist, falls ja, soll keine Differenz angezeigt werden oder sogar die Differenz zu seinem letzten Spiel in der vorigen Saison */



                // Tabelle Reihe für Reihe ausgeben
                echo "<tr>";
                echo "<td>" . $dsatz["id"] . "</td>";
                echo "<td>" . $dsatz["date"] . "</td>";
                echo "<td>" . $dsatz["player1"] . "</td>";
                echo "<td>" . $dsatz["player2"] . "</td>";
                echo "<td>" . $dsatz["score_player1"] . "</td>";
                echo "<td>" . $dsatz["score_player2"] . "</td>";
                echo "<td>" . round($dsatz["elo_player1"]) . " (" . $elodiff_pl1 . ")</td>";
                echo "<td>" . round($dsatz["elo_player2"]) . " (" . $elodiff_pl2 . ")</td>";
                echo "<td>" . $dsatz["ace_set1"] . "</td>";
                echo "<td>" . $dsatz["ace_set2"] . "</td>";
                echo "<td>" . $dsatz["ace_set3"] . "</td>";

                // Löschfunktion nur für das neueste Spiel anzeigen
                if ($dsatz["id"] == $num)
                {
                    echo "<td>
                    <form action='games.php' method='post'>
                        <input type='hidden' name='delete' value='yes' />
                        <input type='hidden' name='id' value=' " . $dsatz["id"] . " ' />
                        <input type='submit' value='Spielergebnis l&ouml;schen' />
                    </form>
                    </td>";
                }
                else
                {
                    echo "<td></td>";
                }

                echo "</tr>";

            }
        ?>
        </tbody>
    </table> <!-- Ende der Tabelle für Anzeige der Spiele -->

</body>
</html>