<?php

require_once(__DIR__ . "/../functions.php");

if(!$_SESSION["id"]) {
    redirect("login.php");
}

$res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level = 0 ORDER BY users.id ASC");

$clients = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('clients.twig', array( 'clients' => $clients ));