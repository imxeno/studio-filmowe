<?php

require_once(__DIR__ . "/functions.php");

redirect_if_not_logged_in();

$user = $DB->query("SELECT id, login, first_name, last_name, phone, address, position FROM users WHERE id = "
    . intval($_SESSION["id"]) . " LIMIT 1")->fetch_assoc();

if(isset($_POST["old_password"]) && isset($_POST["password"]) && isset($_POST["password_2"])) {
    $res = $DB->query("SELECT id FROM users WHERE id = " . $_SESSION["id"] . " AND password = SHA2(CONCAT('" .
        $DB->escape_string($_POST["old_password"]) . "', salt), 512) LIMIT 1")->fetch_assoc();

    if(!$res) {
        echo $twig->render('user.twig', array("user" => $user, "error" => "Stare hasło jest nieprawidłowe."));
        exit();
    }

    if($_POST["password"] !== $_POST["password_2"]) {
        echo $twig->render('user.twig', array("user" => $user, "error" => "Nowe hasło nie zostało poprawnie powtórzone."));
        exit();
    }

    if($_POST["old_password"] === $_POST["password"]) {
        echo $twig->render('user.twig', array("user" => $user, "error" => "Nowe hasło jest identyczne ze starym."));
        exit();
    }

    $len = strlen($_POST["password"]);

    if($len < 6 || $len > 32) {
        echo $twig->render('user.twig', array("user" => $user, "error" => "Nowe hasło powinno mieć od 6 do 32 znaków."));
        exit();
    }

    $salt = generate_salt();

    $DB->query("UPDATE users SET salt='" . $salt . "', password=SHA2(CONCAT('" . $DB->escape_string($_POST["password"]) . "', '" . $salt  . "'), 512) "
        . "WHERE id = " . intval($_SESSION["id"]));

    echo $twig->render('user.twig', array("user" => $user, "success" => "Hasło zostało zmienione."));
    exit();
}

echo $twig->render('user.twig', array("user" => $user));