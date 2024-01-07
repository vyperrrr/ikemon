<?php

session_start();
require_once "storage/UserStorage.php";
require_once "auth.php";

if (!$auth->is_authenticated()) {
    header('Location: index.php');
    exit();
}

$auth->logout();

header('Location: index.php');

?>