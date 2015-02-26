<!DOCTYPE html>
<html>
<head lang="de">
    <meta charset="UTF-8" />
    <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
    <link rel="stylesheet" type="text/css" href="../style/tablestyle/tablestyle.css" />

    <script type="text/javascript">
        $(document).ready(function()
            {
                $("#players").tablesorter({widgets: ['zebra']});
            }
        );
    </script>

    <title>Spielerverwaltung</title>
</head>
<body>

    <h2>Spielerverwaltung</h2>

    <?php include "menu.php" ?>

    <h3>Spieler hinzuf&uuml;gen</h3>

    <form action="players.php" method="post">
        <table>
            <tr>
                <td>Spielername</td>
                <td><input name="name" /></td>
            </tr>
            <tr>
                <td><input type="submit" name="sent" /> <input type="reset" /></td>
            </tr>
         </table>
    </form>



        <?php

        $con = mysqli_connect("localhost", "wuzzler", "wuzzlernoob");
        mysqli_select_db($con, "wuzzeln");


        // Wenn Formular abgesendet, Spieler in Datenbank hinzufügen
        if(isset($_POST["sent"]))
        {
            $con = mysqli_connect("localhost", "wuzzler", "wuzzlernoob");
            mysqli_select_db($con, "wuzzeln");

            $id_last = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM players ORDER BY id DESC LIMIT 1"));
            $id_new = $id_last["id"] + 1;

            $query = "insert into players (id, name) values"
                . "('" . $id_new . "', " . "'" . $_POST["name"] . "')";

            mysqli_query($con, $query);
        }


        // Einträge löschen
        /*
        if (isset($_POST["delete"]) && isset($_POST["id"]))
        {

            $id = $_POST["id"];
            $query = "DELETE FROM players WHERE id='$id'";

            if (!mysqli_query($con, $query))
                echo "L&ouml;schen fehlgeschlagen: $query<br />" . mysql_error() . "<br /><br />";
        }
        */
        ?>

        <h3>Spielerliste</h3>
    <!-- Tabelle für Anzeige der Spieler -->
    <table id="players" class="tablesorter"> <!-- Tabelle Spieleranzeige -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            <!--<th>Optionen</th>     Spieler löschen-->
            </tr>
        </thead>
        <tbody>
        <?php

        $result = mysqli_query($con, "select * from players order by id");
        $num = mysqli_num_rows($result);

        // Datensätze aus Ergebnis ermitteln, in Array speichern und ausgeben
        while($dsatz = mysqli_fetch_assoc($result))
        {
            echo "<tr>";
            echo "<td>" . $dsatz["id"] . "</td>";
            echo "<td>" . $dsatz["name"] . "</td>";

            /* Spieler Löschen
            echo "<td>
                <form action='players.php' method='post'>
                    <input type='hidden' name='delete' value='yes' />
                    <input type='hidden' name='id' value=' " . $dsatz["id"] . " ' />
                    <input type='submit' value='Spieler l&ouml;schen' />
                </form>
			    </td>
             */
            echo "</tr>";


        }
        ?>
        </tbody>
    </table> <!-- Ende der Tabelle für Anzeige der Spieler -->

</body>
</html>