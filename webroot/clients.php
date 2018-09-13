<?php

require_once(__DIR__ . "/../functions.php");

if(!$_SESSION["id"]) {
    redirect("login.php");
}

if(isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address, users.phone FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level = 0 AND users.id = "
     . $id . " LIMIT 1")->fetch_assoc();
     if(!$res) {
        echo $twig->render('error.twig', array( 'error' => ("Klient o ID #" . $id . " nie istnieje.")));
        exit();
     }
     echo $twig->render('client.twig', array('client' => $res));
     exit();
}

$res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level = 0 ORDER BY users.id ASC");

$clients = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('clients.twig', array( 'clients' => $clients ));