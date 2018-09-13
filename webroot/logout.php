<?php

require_once(__DIR__ . "/../functions.php");

session_destroy();

redirect("index.php");