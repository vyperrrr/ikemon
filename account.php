<?php

    session_start();

    require_once "storage/UserStorage.php";
    require_once "storage/CardStorage.php";
    require_once "vendor/Auth.php";

    $auth = new Auth(new UserStorage());
    $storage = new CardStorage();

    if (!$auth->is_authenticated()) {
        header('Location: index.php');
        exit();
    }

    $user = $auth->authenticated_user();
    $cards = $storage->findAll();

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
        <h1><a href="index.php">IKémon</a> > Account</h1>
        <h1><a href="logout.php">Logout</a>
    </header>
    <div id="content">
        <h1><?= $user["username"] ?></h1>
        <h1><?= $user["email"] ?></h1>
        <h1><?= $user["credits"] ?></h1>
        <div id="card-list">
            <?php foreach($cards as $id => $card): ?>
                <?php if($card["owner"] == $user["username"]): ?>
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
                    <a href="sell-card.php?id=<?= $id ?>">
                        <div class="buy">
                            <span class="card-price">
                                <span class="icon">Sell for 💰</span>
                                <?= $card["price"]*0.9 ?></span>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>