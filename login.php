<?php

require_once(__DIR__ . "/functions.php");

if(isset($_POST["login"]) && isset($_POST["password"])) {
    $stmt = $DB->prepare("SELECT id FROM users WHERE login like ? AND password = SHA2(CONCAT(?, salt), 512) LIMIT 1");
    $stmt->bind_param("ss", $_POST["login"], $_POST["password"]);
    $stmt->bind_result($id);
    $stmt->execute();
    if($stmt->fetch()) {
        $_SESSION["id"] = $id;
        if(isset($_GET["return"]))
            redirect($_GET["return"]);
        else
            redirect("index.php");
    }
    $stmt->close();
    echo $twig->render('login.twig', array("error" => true));
    exit();
}

echo $twig->render('login.twig');