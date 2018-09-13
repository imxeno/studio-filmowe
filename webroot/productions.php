<?php

require_once(__DIR__ . "/../functions.php");

redirect_if_not_logged_in();

if(isset($_GET["id"])) {

    $id = intval($_GET["id"]);
    $res = $DB->query("SELECT id, name, premiere, genre, costs, contracting_id FROM productions WHERE id = " . $id . " LIMIT 1")->fetch_assoc();
     if(!$res) {
        echo $twig->render('error.twig', array( 'error' => ("Produkcja o ID #" . $id . " nie istnieje.")));
        exit();
     }
     echo $twig->render('production.twig', array('production' => $res));
     exit();
}

$res = $DB->query("SELECT productions.id, productions.name, productions.premiere, productions.genre, productions.costs,"
     . " contracting_id, users.first_name as contracting_first_name,"
     . " users.last_name as contracting_last_name FROM productions INNER JOIN users ON users.id = productions.contracting_id");

$productions = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('productions.twig', array( 'productions' => $productions ));