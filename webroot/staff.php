<?php

require_once(__DIR__ . "/../functions.php");

redirect_if_not_logged_in();

if(isset($_GET["id"])) {

    $positions = $DB->query("SELECT id, name, access_level FROM positions WHERE access_level > 0 ORDER BY access_level DESC")->fetch_all(MYSQLI_ASSOC);

    $id = intval($_GET["id"]);
    $res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address, users.phone, users.position, users.salary," 
     . " users.agreement_signed, users.agreement_expires FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level > 0 AND users.id = "
     . $id . " LIMIT 1")->fetch_assoc();
     if(!$res) {
        echo $twig->render('error.twig', array( 'error' => ("Pracownik o ID #" . $id . " nie istnieje.")));
        exit();
     }
     $res["salary"] = number_format($res["salary"], 2, '.', '');
     $rgx = "/(\d+-\d+-\d+) (\d+:\d+):\d+/";
     $rpl = "$1T$2";
     $res["agreement_signed"] = preg_replace($rgx, $rpl, $res["agreement_signed"]);
     $res["agreement_expires"] = preg_replace($rgx, $rpl, $res["agreement_expires"]);
     echo $twig->render('worker.twig', array('worker' => $res, 'positions' => $positions));
     exit();
}

$res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address, positions.name AS position_name FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level > 0 ORDER BY users.id ASC");

$staff = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('staff.twig', array( 'staff' => $staff ));