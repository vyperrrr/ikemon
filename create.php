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

$user = $userStorage->findById($auth->authenticated_user()["id"]);

if(!in_array("admin", $user["roles"])){
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]) ?? '';
    $type=  trim($_POST["type"]) ?? '';
    $hp = trim($_POST["hp"]) ?? '';
    $attack = trim($_POST["attack"]) ?? '';
    $defense = trim($_POST["defense"]) ?? '';
    $price = trim($_POST["price"]) ?? '';
    $description = trim($_POST["description"]) ?? '';
    $image = trim($_POST["image"]) ?? '';
    
    $errors = [];

    if (empty($name)) {
        $errors["name"] = "name is empty";
    } 

    if (empty($type)) {
        $errors["type"] = "type is empty";
    } 

    if (empty($hp)) {
        $errors["hp"] = "hp is empty";
    }elseif(!filter_var($hp, FILTER_VALIDATE_INT)){
        $errors["hp"] = "hp must be a number";
    }

    if (empty($attack)) {
        $errors["attack"] = "attack is empty";
    }elseif(!filter_var($attack, FILTER_VALIDATE_INT)){
        $errors["attack"] = "attack must be a number";
    }

    if (empty($defense)) {
        $errors["defense"] = "defense is empty";
    }elseif(!filter_var($defense, FILTER_VALIDATE_INT)){
        $errors["defense"] = "defense must be a number";
    }

    if (empty($price)) {
        $errors["price"] = "price is empty";
    }elseif(!filter_var($price, FILTER_VALIDATE_INT)){
        $errors["price"] = "price must be a number";
    }

    if (empty($description)) {
        $errors["description"] = "description is empty";
    }

    if (empty($image)) {
        $errors["image"] = "image is empty";
    }

    if(count($errors) == 0){
        $cardStorage->add([
            "name" => $name, 
            "type" => $type,
            "hp" => $hp,
            "attack" => $attack,
            "defense" => $defense,
            "price" => $price,
            "description" => $description,
            "image" => $image,
            "owner" => $user["username"]
        ]);
    }
}

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
        <h1><a href="index.php">IKémon</a> > Create</h1>
        <h1><a href="logout.php">Logout</a>
    </header>
    <div id="content">
    <form action="create.php" method="post" novalidate>
            <h1>Make a new pokemon card</h1>
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value=<?= $name ?? '' ?>>
            </div>
            <?php if (isset($errors["name"])) : ?><span class="error"><?= $errors["name"] ?></span><?php endif; ?>
            <div>
                <select name="type" id="type">
                    <?php $types= ["normal","fire","water","electric","grass","ice","fighting","poison","ground","psychic","bug","rock","ghost","dark","steel"]; ?>
                    <?php foreach($types as $type): ?>
                    <option value=<?= $type ?>>
                        <?= $type ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (isset($errors["type"])) : ?><span class="error"><?= $errors["type"] ?></span><?php endif; ?>
            <div>
                <label for="hp">Health:</label>
                <input type="text" name="hp" id="hp" value=<?= $hp ?? '' ?>>
            </div>
            <?php if (isset($errors["hp"])) : ?><span class="error"><?= $errors["hp"] ?></span><?php endif; ?>
            <div>
                <label for="attack">Attack:</label>
                <input type="text" name="attack" id="attack" value=<?= $attack ?? '' ?>>
            </div>
            <?php if (isset($errors["attack"])) : ?><span class="error"><?= $errors["attack"] ?></span><?php endif; ?>
            <div>
                <label for="defense">Defense:</label>
                <input type="text" name="defense" id="defense" value=<?= $defense ?? '' ?>>
            </div>
            <?php if (isset($errors["defense"])) : ?><span class="error"><?= $errors["defense"] ?></span><?php endif; ?>
            <div>
                <label for="price">Price:</label>
                <input type="text" name="price" id="price" value=<?= $price ?? '' ?>>
            </div>
            <?php if (isset($errors["price"])) : ?><span class="error"><?= $errors["price"] ?></span><?php endif; ?>
            <div>
                <label for="description">Description:</label>
                <textarea name="description" id="description">
                    <?= $description ?? '' ?>
                </textarea>
            </div>
            <?php if (isset($errors["description"])) : ?><span class="error"><?= $errors["description"] ?></span><?php endif; ?>
            <div>
                <label for="image">Image:</label>
                <input type="text" name="image" id="image" value=<?= $image ?? '' ?>>
            </div>
            <?php if (isset($errors["image"])) : ?><span class="error"><?= $errors["image"] ?></span><?php endif; ?><br>
            <button type="submit">Create</button>
        </form>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>