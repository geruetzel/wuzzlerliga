<?php

    // stellt eine Verbindung mit der Mysqldatenbank her
    function dbConnect()
    {
        // Zugangsdaten zum MySQL Server
        $con = mysqli_connect("localhost", "USER", "PASSWORD");
        // wähle gewünschte Datenbank aus
        mysqli_select_db($con, "DATENBANK");
        return $con;
    }

    // gibt alle Namen der Spieler in einem numerischen Array zurück;
    function getPlayerNames()
    {
        $con = dbConnect();
        $result = mysqli_query($con, "select name from players order by name");

        while ($erg = mysqli_fetch_assoc($result))
        {
            $players[] = $erg["name"];
        }

    return $players; //Array $players
    }

    // gibt einen Spielernamen zurück, der zur gegebenen ID gefunden wird
    function getPlayer($id)
    {
        $con = dbConnect();

        $result = mysqli_query($con, "select name from players where id = '$id'");
        $erg = mysqli_fetch_assoc($result);

        $player = $erg["name"];

        return $player; //String $player
    }

    function getPlayerId($playername)
    {
        $con = dbConnect();

        $result = mysqli_query($con, "select id from players where name = '$playername'");
        $erg = mysqli_fetch_assoc($result);

        $id = $erg["id"];

        return $id; //String $player
    }

    // errechnet die Winrate eines Spielers $spieler
    function getWinRate($player)
    {

        $con = dbConnect();

        //anzahl der spiele ermitteln
        $games_played = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE player1 = '$player' OR player2 = '$player'"));

        //anzahl der wins ermitteln
        $games_won_as_pl1 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE player1 = '$player' AND score_player1 = '2'"));
        $games_won_as_pl2 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE player2 = '$player' AND score_player2 = '2'"));
        $games_won = $games_won_as_pl1 + $games_won_as_pl2;

        //winrate = gewonnene spiele / gespielte spiele
        $winrate = $games_won / $games_played;

        $winrate = $winrate * 100;
        $winrate = round($winrate, 2);
        return $winrate;
    }

    function getPlayerElo($player)
    {
        $con = dbConnect();

        $result = mysqli_query($con, "select elo_current from players where name = '$player'");
        $erg = mysqli_fetch_assoc($result);

        $elo = $erg["elo_current"];

        return $elo; //String $player
    }

    function getPlayerWins($player) {

        $con = dbConnect();

        $games_won_as_pl1 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE player1 = '$player' AND score_player1 = '2'"));
        $games_won_as_pl2 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE player2 = '$player' AND score_player2 = '2'"));
        $games_won = $games_won_as_pl1 + $games_won_as_pl2;

        return $games_won;
    }

    function getPlayerGames($player)
    {
        $con = dbConnect();

        //anzahl der spiele ermitteln
        $games_played = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE player1 = '$player' OR player2 = '$player'"));

        return $games_played;
    }

    function getPlayerSets($player)
    {
        $con = dbConnect();

        $result = mysqli_query($con, "SELECT score_player1 FROM games WHERE player1 = '$player' OR player2 = '$player'");
        $result1 = mysqli_query($con, "SELECT score_player2 FROM games WHERE player1 = '$player' OR player2 = '$player'");
        $sets_pl1 = 0;
        $sets_pl2 = 0;

        while($dsatz = mysqli_fetch_assoc($result))
        {
            $sets_pl1 = $sets_pl1 + $dsatz["score_player1"];
        }

        while($dsatz = mysqli_fetch_assoc($result1))
        {
            $sets_pl2 = $sets_pl2 + $dsatz["score_player2"];
        }

        $sets_played = $sets_pl1 + $sets_pl2;

        return $sets_played;
    }

    function getZuNulls($player)
    {
        $con = dbConnect();

        $aces_in_set_1 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE ace_set1 = '$player'"));
        $aces_in_set_2 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE ace_set2 = '$player'"));
        $aces_in_set_3 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM games WHERE ace_set3 = '$player'"));

        $aces = $aces_in_set_1 + $aces_in_set_2 + $aces_in_set_3;

        return $aces;
    }

    function getLastElo($player)
    {
        $con = dbConnect();

        // Welches Spiel hat $player vor dem letzten Spiel gespielt?
        $query = "select * from games where player1 = '" . $player . "' or player2 = '" . $player . "' order by id desc limit 1,1";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);

        // War $player player1 oder player2? Je nachdem wird aus der query die alte Elo herausgenommen
        if ($player == $data["player1"])
            $elo_last = $data["elo_player1"];
        else
            $elo_last = $data["elo_player2"];

        return $elo_last;
    }

    function getLastEloFromCertainGame($player, $id)
    {

        /*Die Funktion getLastElo() sucht für einen Spieler die letzte ELO, gesehen aus dem aktuellen Standpunkt
        Wenn jedoch die letzte ELO eines Spielers vom Standpunkt eines anderen Games gefunden werden soll, so
        wir diese Funktion verwendet

        $id ist somit das "neueste" Spiel, der neue Standpunkt

        im query von getlastelo müssten einfach die neuesten spiele seit spiel $id ausgeblendet werden, dann hätten wir schon das ergebnis!

         */

        $con = dbConnect();

        // Welches Spiel hat $player vor dem letzten Spiel gespielt?
        $query = "select * from games where (player1 = '" . $player . "' or player2 = '" . $player . "') and id <= '" . $id . "' order by id desc limit 1,1";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);

        // War $player player1 oder player2? Je nachdem wird aus der query die alte Elo herausgenommen
        if ($player == $data["player1"])
            $elo_last = $data["elo_player1"];
        else
            $elo_last = $data["elo_player2"];

        // Wenn kein Wert für die letzte ELO ermittelt werden kann, so war die ELO 1000 (Ausgangswert für neue Spieler)

        if ($elo_last == 0)
            $elo_last = 1000;


        return $elo_last;
    }

    function postToHipChat($message, $color)
    {
        // Post Parameter in Array schreiben
        $post = array(
            "color" => $color,
            "notify" => "true",
            "message" => $message,
            "from" => "",
        );

        // dieses Array json-encoden
        $post = json_encode($post);

        // http header für json content setzen
        $header = array(
            "Content-type: application/json"
        );

        // post
        $url = "https://api.hipchat.com/v2/room/1202360/notification?auth_token=FXulxAawmKShFulUhbvStim48FGmHRl2GYPpUHaz";
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, $header);
        curl_setopt($c, CURLOPT_POSTFIELDS, $post);

        echo curl_exec($c);
    }
