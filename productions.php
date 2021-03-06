<?php

require_once(__DIR__ . "/functions.php");

redirect_if_not_logged_in();

if(isset($_POST["submit"])) {
    if(intval($_POST["id"]) === 0) {
        $DB->query("INSERT INTO productions (`name`, `genre`, `premiere`, `costs`, `contracting_id`) VALUES ('" . $DB->escape_string($_POST["name"])
        . "', '" . $DB->escape_string($_POST["genre"]) . "', '" . $DB->escape_string($_POST["premiere"]) . "', '" . $DB->escape_string($_POST["costs"])
        . "', '" . $DB->escape_string($_POST["contracting_id"]) . "')");
        redirect("productions.php?id=" . $DB->insert_id);
    } else {
        $DB->query("UPDATE productions SET `name`='" . $DB->escape_string($_POST["name"]) . "', `genre`='" . $DB->escape_string($_POST["genre"])
        . "', premiere='" . $DB->escape_string($_POST["premiere"]) . "', costs='" . $DB->escape_string($_POST["costs"]) . "', contracting_id='"
        . $DB->escape_string($_POST["contracting_id"]) . "' WHERE id = " . intval($_POST["id"]) . " LIMIT 1");
    }
}

if(isset($_GET["new"])) {
    echo $twig->render('production.twig');
    exit();
}
else if(isset($_GET["id"])) {

    $id = intval($_GET["id"]);
    $res = $DB->query("SELECT id, name, premiere, genre, costs, contracting_id FROM productions WHERE id = " . $id . " LIMIT 1")->fetch_assoc();
     if(!$res) {
        echo $twig->render('error.twig', array( 'error' => ("Produkcja o ID #" . $id . " nie istnieje.")));
        exit();
     }

     if(isset($_GET["delete"])) {
         $DB->query("DELETE FROM productions WHERE id = " . intval($id));
         echo $twig->render('success.twig', array( 'msg' => ("Produkcja o ID #" . $id . " został usunięty z bazy danych.")));
         exit();
     }

     $rgx = "/(\d+-\d+-\d+) (\d+:\d+):\d+/";
     $rpl = "$1";
     $res["premiere"] = preg_replace($rgx, $rpl, $res["premiere"]);

     echo $twig->render('production.twig', array('production' => $res));
     exit();
}

$res = $DB->query("SELECT productions.id, productions.name, productions.premiere, productions.genre, productions.costs,"
     . " contracting_id, users.first_name as contracting_first_name,"
     . " users.last_name as contracting_last_name FROM productions INNER JOIN users ON users.id = productions.contracting_id");

$productions = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('productions.twig', array( 'productions' => $productions, 'modify_access' => ($ACCESS_LEVEL > 200) ));