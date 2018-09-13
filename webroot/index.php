<?php

require_once(__DIR__ . "/../functions.php");

redirect_if_not_logged_in();

echo $twig->render('home.twig');