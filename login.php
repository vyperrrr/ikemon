<?php

session_start();
require_once "storage/UserStorage.php";
require_once "auth.php";

if ($auth->is_authenticated()) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userStorage = new UserStorage();

    $username = trim($_POST["username"]) ?? '';
    $password = trim($_POST["password"]) ?? '';

    $errors = [];

    //Error handling

    if (empty($username)) {
        $errors["username"] = "Username is empty";
    }

    if (empty($password)) {
        $errors["password"] = "Password is empty";
    }

    //Operations

    if (count($errors) === 0) {
        $user = $auth->authenticate($username, $password);

        if (!is_null($user)) {
            $auth->login($user);

            header("Location: index.php");
            exit();
        } else {
            $errors['invalid'] = "Wrong username or password";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Login</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Login</h1>
    </header>
    <div id="content">
        <form action="login.php" method="post" novalidate>
            <h1>Sign In</h1>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value=<?= $username ?? '' ?>>
            </div>
            <?php if (isset($errors["username"])) : ?><span class="error"><?= $errors["username"] ?></span><?php endif; ?>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value=<?= $password ?? '' ?>>
            </div>
            <?php if (isset($errors["password"])) : ?><span class="error"><?= $errors["password"] ?></span><?php endif; ?>
            <?php if (isset($errors['invalid'])) : ?>
                <div class='error'>
                    <?= $errors['invalid'] ?>
                </div>
            <?php endif; ?>
            <button type="submit">Login</button>
            <footer>Not a member? <a href="register.php">Sign up here</a></footer>
        </form>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>