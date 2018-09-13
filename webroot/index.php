<?php

require_once(__DIR__ . "/../functions.php");

if(!$_SESSION["id"]) {
    redirect("login.php");
}

echo $twig->render('home.twig');