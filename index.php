<?php

require_once(__DIR__ . "/functions.php");

redirect_if_not_logged_in();

$staff_count = $DB->query("SELECT COUNT(users.id) as count FROM users INNER JOIN positions ON positions.id = users.position"
    . " WHERE positions.access_level > 0")->fetch_assoc()["count"];

$client_count = $DB->query("SELECT COUNT(users.id) as count FROM users INNER JOIN positions ON positions.id = users.position"
    . " WHERE positions.access_level = 0")->fetch_assoc()["count"];

$productions_count = $DB->query("SELECT COUNT(productions.id) as count FROM productions")->fetch_assoc()["count"];

$stats = array(
    array(
        "name" => "Liczba pracowników studia w systemie",
        "value" => $staff_count
    ),
    array(
        "name" => "Liczba klientów w systemie",
        "value" => $client_count
    ),
    array(
        "name" => "Liczba produkcji w systemie",
        "value" => $productions_count
    )
);

echo $twig->render('home.twig', array("stats" => $stats));