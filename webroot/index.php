<?php

require_once(__DIR__ . "/../functions.php");

echo $twig->render('login.twig', array('the' => 'variables', 'go' => 'here'));