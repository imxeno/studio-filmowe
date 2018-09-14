<?php

session_start();

require_once(__DIR__ . "/vendor/autoload.php");

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader, array(
    //'cache' => '/path/to/compilation_cache',
));

$DB = mysqli_connect("host", "login", "password", "db");
$DB->set_charset("utf8mb4");

if(isset($_SESSION["id"])) {
    $ACCESS_LEVEL = intval($DB->query("SELECT positions.access_level FROM users INNER JOIN positions ON users.position = positions.id WHERE users.id = " . $_SESSION["id"] . " LIMIT 1")
    ->fetch_assoc()["access_level"]);
}

function redirect($page) {
    header("Location: " . $page);
}

function redirect_if_not_logged_in() {
    if(!$_SESSION["id"]) {
        redirect("login.php?return=" . urlencode($_SERVER["REQUEST_URI"]));
        exit();
    }
}

function generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generate_salt() {
    return generate_random_string(32);
}