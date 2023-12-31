<?php

    $id = isset($_GET["id"]) ? $_GET["id"] : '';

    if(empty($id))
    {
        header("Location: index.php");
        exit();
    }
        
    require './storage/CardStorage.php';
    
    $storage = new CardStorage();

    $card = $storage->findById($id);

    if(!isset($card)) 
    {
        header("Location: index.php");
        exit();
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= $card["name"] ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > <?= $card["name"] ?></h1>
    </header>
    <div id="content">
        <div id="details">
            <div class="image clr-<?= $card["type"] ?>">
                <img src=<?= $card["image"] ?> alt=<?= $card["name"] ?>>
            </div>
            <div class="info">
                <div class="description">
                    <?= $card["description"] ?>  
                </div>
                <span class="card-type">
                    <span class="icon">🏷</span> 
                    Type: <?= $card["type"] ?>
                </span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> <?= $card["hp"] ?></div>
                    <div class="card-attack"><span class="icon">⚔</span> <?= $card["attack"] ?></div>
                    <div class="card-defense"><span class="icon">🛡</span> <?= $card["defense"] ?></div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>