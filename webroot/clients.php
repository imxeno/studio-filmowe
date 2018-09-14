<?php

require_once(__DIR__ . "/../functions.php");

redirect_if_not_logged_in();

if(isset($_POST["submit"])) {
    if(intval($_POST["id"]) === 0) {
        $DB->query("INSERT INTO users (`first_name`, `last_name`, `address`, `phone`) VALUES ('" . $DB->escape_string($_POST["first_name"])
        . "', '" . $DB->escape_string($_POST["last_name"]) . "', '" . $DB->escape_string($_POST["address"]) . "', '" . $DB->escape_string($_POST["phone"]) . "')");
        redirect("clients.php?id=" . $DB->insert_id);
    } else {
        $DB->query("UPDATE users SET `first_name`='" . $DB->escape_string($_POST["first_name"]) . "', `last_name`='" . $DB->escape_string($_POST["last_name"])
        . "', address='" . $DB->escape_string($_POST["address"]) . "', phone='" . $DB->escape_string($_POST["phone"]) . "' WHERE id = " . intval($_POST["id"]) . " LIMIT 1");
    }
}

if(isset($_GET["new"])) {
    echo $twig->render('client.twig');
    exit();
}
else if(isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address, users.phone FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level = 0 AND users.id = "
     . $id . " LIMIT 1")->fetch_assoc();
     if(!$res) {
        echo $twig->render('error.twig', array( 'error' => ("Klient o ID #" . $id . " nie istnieje.")));
        exit();
     }

     if(isset($_GET["delete"])) {
         $DB->query("DELETE FROM users WHERE id = " . intval($id));
         echo $twig->render('success.twig', array( 'msg' => ("Klient o ID #" . $id . " został usunięty z bazy danych.")));
         exit();
     }

     echo $twig->render('client.twig', array('client' => $res));
     exit();
}

$res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level = 0 ORDER BY users.id ASC");

$clients = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('clients.twig', array( 'clients' => $clients, 'modify_access' => ($ACCESS_LEVEL > 200) ));