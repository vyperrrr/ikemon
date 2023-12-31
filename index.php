<?php

    session_start();

    require_once "vendor/Auth.php";
    require_once "storage/CardStorage.php";
    require_once "storage/UserStorage.php";

    $auth = new Auth(new UserStorage());

    $storage = new CardStorage();
    $cards = $storage -> findAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Home</h1>
        <?php if(!$auth->is_authenticated()): ?>
            <h1><a href="login.php">Login</a>
            <h1><a href="register.php">Register</a>
        <?php else: ?>
            <?php
                $user = $auth->authenticated_user();
            ?>
            <h1>Welcome, <a href="account.php"><?= $user["username"] ?></a>
            <h1>Credits: <?= $user["credits"] ?></h1>
            <h1><a href="logout.php">Logout</a>
        <?php endif; ?>
    </header>
    <div id="content">
        <div id="card-list">
            <?php foreach($cards as $id => $card): ?>
                <div class="pokemon-card">
                <div class="image clr-<?= $card["type"] ?>">
                    <img src=<?= $card["image"] ?> alt=<?= $card["name"] ?>>
                </div>
                <div class="details">
                    <h2>
                        <a href="details.php?id=<?= $id ?>">
                            <?= $card["name"] ?>
                        </a>
                    </h2>
                    <span class="card-type">
                        <span class="icon">🏷</span>
                        <?= $card["type"] ?>
                    </span>
                    <span class="attributes">
                        <span class="card-hp">
                            <span class="icon">❤</span>
                            <?= $card["hp"] ?>
                        </span>
                        <span class="card-attack">
                            <span class="icon">⚔</span>
                            <?= $card["attack"] ?>
                        </span>
                        <span class="card-defense">
                            <span class="icon">🛡</span>
                            <?= $card["defense"] ?>
                        </span>
                    </span>
                </div>
                <?php if($auth->is_authenticated() && $card["owner"] == "admin" && $auth->authenticated_user()["username"] != "admin"): ?>
                    <div class="buy">
                        <span class="card-price">
                            <span class="icon">Buy for 💰</span>
                            <?= $card["price"] ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>