<?php

session_start();

require_once(__DIR__ . "/vendor/autoload.php");

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader, array(
    //'cache' => '/path/to/compilation_cache',
));

$DB = mysqli_connect("217.182.77.253", "studio", "y9B94ffI1V02FHbS", "studio");
$DB->set_charset("utf8mb4");

function redirect($page) {
    header("Location: " . $page);
}

function redirect_if_not_logged_in() {
    if(!$_SESSION["id"]) {
        redirect("login.php?return=" . urlencode($_SERVER["REQUEST_URI"]));
        exit();
    }
}