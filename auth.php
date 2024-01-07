<?php

require_once "storage/UserStorage.php";
require_once "vendor/Auth.php";

$auth = new Auth(new UserStorage());

?>
