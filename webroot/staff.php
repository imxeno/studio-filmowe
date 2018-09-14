<?php

require_once(__DIR__ . "/../functions.php");

redirect_if_not_logged_in();

$positions = $DB->query("SELECT id, name, access_level FROM positions WHERE access_level > 0 ORDER BY access_level DESC")->fetch_all(MYSQLI_ASSOC);

if(isset($_POST["submit"])) {

    $agreement_type = intval($_POST["agreement_type"]);

    if($agreement_type === 1)
    {
        $agreement_expires = "'" . $DB->escape_string($_POST["agreement_expires"]) . "'";
    } else {
        $agreement_expires = "NULL";
    }

    if(intval($_POST["id"]) === 0) {
        if(!isset($_POST["login"]) || $_POST["login"] === '') {
            echo $twig->render('error.twig', array( 'error' => ("Nazwa użytkownika nowego pracownika nie może być pusta.")));
            exit();
        }
        if(!isset($_POST["password"]) || $_POST["password"] === '') {
            echo $twig->render('error.twig', array( 'error' => ("Hasło nowego pracownika nie może być puste.")));
            exit();
        }
        $DB->query("INSERT INTO users (`login`, `first_name`, `last_name`, `address`, `phone`, `position`, `salary`, `agreement_signed`, `agreement_expires`) VALUES (" 
        . "'" . $DB->escape_string($_POST["login"]). "', "
        . "'" . $DB->escape_string($_POST["first_name"]). "', "
        . "'" . $DB->escape_string($_POST["last_name"]) . "', "
        . "'" . $DB->escape_string($_POST["address"]) . "', "
        . "'" . $DB->escape_string($_POST["phone"]) . "', "
        . "'" . $DB->escape_string($_POST["position"]) . "', "
        . "'" . $DB->escape_string($_POST["salary"]) . "', "
        . "'" . $DB->escape_string($_POST["agreement_signed"]) . "', "
        . $agreement_expires
        . ");");
        
        $id = $DB->insert_id;
        $salt = generate_salt();

        $DB->query("UPDATE users SET salt='" . $salt . "', password=SHA2(CONCAT('" . $DB->escape_string($_POST["password"]) . "', '" . $salt  . "'), 512) "
        . "WHERE id = " . $id) . " LIMIT 1";
    
        redirect("staff.php?id=" . $id);
    } else {
        $id = intval($_POST["id"]);
        $DB->query("UPDATE users SET " 
        . "login='" . $DB->escape_string($_POST["login"]). "', "
        . "first_name='" . $DB->escape_string($_POST["first_name"]). "', "
        . "last_name='" . $DB->escape_string($_POST["last_name"]) . "', "
        . "address='" . $DB->escape_string($_POST["address"]) . "', "
        . "phone='" . $DB->escape_string($_POST["phone"]) . "', "
        . "position='" . $DB->escape_string($_POST["position"]) . "', "
        . "salary='" . $DB->escape_string($_POST["salary"]) . "', "
        . "agreement_signed='" . $DB->escape_string($_POST["agreement_signed"]) . "', "
        . "agreement_expires=" . $agreement_expires . " "
        . " WHERE id = " . $id . " LIMIT 1");

        if(isset($_POST["password"]) && $_POST["password"] !== '') {
            $salt = generate_salt();
    
            $DB->query("UPDATE users SET salt='" . $salt . "', password=SHA2(CONCAT('" . $DB->escape_string($_POST["password"]) . "', '" . $salt  . "'), 512) "
            . "WHERE id = " . $id) . " LIMIT 1";
    
        }
    }
    
}

if(isset($_GET["new"])) {
    echo $twig->render('worker.twig', array( 'positions' => $positions ));
    exit();
}
else if(isset($_GET["id"])) {

    $id = intval($_GET["id"]);
    $res = $DB->query("SELECT users.id, users.login, users.first_name, users.last_name, users.address, users.phone, users.position, users.salary," 
     . " users.agreement_signed, users.agreement_expires FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level > 0 AND users.id = "
     . $id . " LIMIT 1")->fetch_assoc();
     if(!$res) {
        echo $twig->render('error.twig', array( 'error' => ("Pracownik o ID #" . $id . " nie istnieje.")));
        exit();
     }

    if(isset($_GET["delete"])) {
        $DB->query("DELETE FROM users WHERE id = " . intval($id));
        echo $twig->render('success.twig', array( 'msg' => ("Pracownik o ID #" . $id . " został usunięty z bazy danych.")));
        exit();
    }

     $res["salary"] = number_format($res["salary"], 2, '.', '');
     $rgx = "/(\d+-\d+-\d+) (\d+:\d+):\d+/";
     $rpl = "$1";
     $res["agreement_signed"] = preg_replace($rgx, $rpl, $res["agreement_signed"]);
     $res["agreement_expires"] = preg_replace($rgx, $rpl, $res["agreement_expires"]);
     echo $twig->render('worker.twig', array('worker' => $res, 'positions' => $positions));
     exit();
}

$res = $DB->query("SELECT users.id, users.first_name, users.last_name, users.address, positions.name AS position_name FROM users"
     . " INNER JOIN positions ON users.position = positions.id WHERE positions.access_level > 0 ORDER BY users.id ASC");

$staff = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('staff.twig', array( 'staff' => $staff ));