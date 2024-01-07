<?php

session_start();
require_once "storage/CardStorage.php";
require_once "storage/UserStorage.php";
require_once "auth.php";

$cardStorage = new CardStorage();
$userStorage = new UserStorage();

$user;

$types= [];

foreach($cardStorage->findAll() as $card)
{
    $type = $card["type"];
    if(!in_array($type, $types))
        $types[] = $type;
}

$filtered__cards = [];

if(isset($_GET["type"]) && $_GET["type"] != "all")
{
    $filter = $_GET["type"];
    foreach($cardStorage->findAll() as $card)
    {
        if($card["type"] == $filter)
            $filtered__cards[] = $card;
    }
}
else $filtered__cards = $cardStorage->findAll();

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
        <?php if (!$auth->is_authenticated()) : ?>
            <h1><a href="login.php">Login</a>
            <h1><a href="register.php">Register</a>
            <?php else : ?>
                <?php
                    $user = $userStorage->findById($auth->authenticated_user()["id"])
                ?>
                <h1>Welcome, <a href="account.php"><?= $user["username"] ?></a>
                <h1>Credits: <?= $user["credits"] ?></h1>
                <h1><a href="logout.php">Logout</a>
            <?php endif; ?>
    </header>
    <div id="content">
        <form action="index.php" method="get">
            <select name="type" id="type" onchange="this.form.submit()">
                <option value="all" selected>
                    all
                </option>
                <?php foreach($types as $type): ?>
                    <option value=<?= $type ?> <?php if(isset($_GET['type']) && $_GET['type'] == $type) echo 'selected'; ?>>
                        <?= $type ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if($auth->is_authenticated()): ?>
            <?php if(in_array("admin", $user["roles"])): ?>
            <h1><a href="create.php">➕ Create new card</a></h1>
            <?php endif; ?>
        <?php endif; ?>
        <div id="card-list">
            <?php foreach ($filtered__cards as $card) : ?>
                    <div class="pokemon-card">
                        <div class="image clr-<?= $card["type"] ?>">
                            <img src=<?= $card["image"] ?> alt=<?= $card["name"] ?>>
                        </div>
                        <div class="details">
                            <h2>
                                <a href="details.php?id=<?= $card["id"] ?>">
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
                    <?php if ($auth->is_authenticated() && $card["owner"] == "admin") : ?>
                        <a href="buy.php?id=<?= $card["id"] ?>">
                            <div class="buy">
                                <span class="card-price">
                                    <span class="icon">Buy for 💰</span>
                                    <?= $card["price"] ?></span>
                            </div>
                        </a>
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