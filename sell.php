<?php

session_start();

require_once "storage/UserStorage.php";
require_once "storage/CardStorage.php";
require_once "auth.php";

$userStorage = new UserStorage();
$cardStorage = new CardStorage();

if (!$auth->is_authenticated()) {
    header('Location: index.php');
    exit();
}

$id = isset($_GET["id"]) ? $_GET["id"] : '';

if (empty($id)) {
    header("Location: index.php");
    exit();
}

//Id of card to sell
$id = $_GET["id"];

//Find card by card id
$card = $cardStorage->findById($id);

//Handle if not found
if (empty($id)) {
    header('Location: index.php');
    exit();
}

//Get current user
$uid = $auth->authenticated_user()["id"];
$user = $userStorage->findById($uid);

//Update credits
$user["credits"] += $card["price"] * 0.9;

//DOESNT WORK FOR SOME REASON
$userStorage->update($uid, $user);

//Give card to admin
$card["owner"] = "admin";

//Update card
$cardStorage->update($id, $card);

header('Location: account.php');
exit();

?>
