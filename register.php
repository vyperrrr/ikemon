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
    $email =  trim($_POST["email"]) ?? '';
    $password = trim($_POST["password"]) ?? '';
    $passwordAgain = trim($_POST["passwordAgain"]) ?? '';

    $errors = [];

    //Error handling

    if (empty($username)) {
        $errors["username"] = "Username is empty";
    } else if ($auth->user_exists($username)) {
        $errors["username"] = "User already exists";
    }

    if (empty($email)) {
        $errors["email"] = "Email is empty";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Bad email format";
    }

    if (empty($password)) {
        $errors["password"] = "Password is empty";
    }

    if (empty($passwordAgain)) {
        $errors["password"] = "Password is empty";
    }

    if ($password != $passwordAgain) {
        $errors["password"] = "Passwords do not match";
    }

    //Operations

    if (count($errors) === 0) {
        //Register user

        $auth->register([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'credits' => 1000
        ]);

        //Auto login

        $user = $auth->authenticate($username, $password);
        $auth->login($user);

        header("Location: index.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Register</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Register</h1>
    </header>
    <div id="content">
        <form action="register.php" method="post" novalidate>
            <h1>Sign Up</h1>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value=<?= $username ?? '' ?>>
            </div>
            <?php if (isset($errors["username"])) : ?><span class="error"><?= $errors["username"] ?></span><?php endif; ?>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value=<?= $email ?? '' ?>>
            </div>
            <?php if (isset($errors["email"])) : ?><span class="error"><?= $errors["email"] ?></span><?php endif; ?>
            <div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" value=<?= $password ?? '' ?>>
                </div>
                <div>
                    <label for="passwordAgain">Password Again:</label>
                    <input type="password" name="passwordAgain" id="passwordAgain" value=<?= $passwordAgain ?? '' ?>>
                </div>
                <?php if (isset($errors["password"])) : ?><span class="error"><?= $errors["password"] ?></span><?php endif; ?>
            </div>
            <button type="submit">Register</button>
            <footer>Already a member? <a href="login.php">Sign in here</a></footer>
        </form>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>