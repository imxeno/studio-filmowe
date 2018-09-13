<?php

require_once(__DIR__ . "/../functions.php");

redirect_if_not_logged_in();

$res = $DB->query("SELECT productions.id, productions.name, productions.premiere, productions.genre, productions.costs,"
     . " contracting_id, users.first_name as contracting_first_name,"
     . " users.last_name as contracting_last_name FROM productions INNER JOIN users ON users.id = productions.contracting_id");

$productions = $res->fetch_all(MYSQLI_ASSOC);

echo $twig->render('productions.twig', array( 'productions' => $productions ));