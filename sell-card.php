<?php

    session_start();

    require_once "storage/UserStorage.php";
    require_once "storage/CardStorage.php";
    require_once "vendor/Auth.php";

    $auth = new Auth(new UserStorage());
    $ustorage = new UserStorage();
    $storage = new CardStorage();

    if (!$auth->is_authenticated()) {
        header('Location: index.php');
        exit();
    }

    $user = $auth->authenticated_user();

    if(isset($_GET["id"]))
    {
        //Id of card to sell
        $id = $_GET["id"];

        //Find card by card id
        $card = $storage->findById($id);

        //Handle if not found
        if(empty($id))
        {
            header('Location: index.php');
            exit();
        }

        //Get current user
        $user_to_update = $ustorage->findById($user["id"]);

        //Update credits
        $user_to_update["credits"] += $card["price"]*0.9;

        //DOESNT WORK FOR SOME REASON
        $ustorage->update($user["id"], $user_to_update);

        //Give card to admin
        $card["owner"] = "admin";

        //Update card
        $storage->update($id, $card);

        header('Location: index.php');
        exit();
    }

?>
