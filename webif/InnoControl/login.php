<?php
header('Access-Control-Allow-Origin: *');

session_start();
$datei = "/var/www/version.txt"; // Name der Datei
$version = file($datei); // Datei in ein Array einlesen

$passwordFile = file("/opt/innotune/settings/web_settings.txt");
$syspassword = trim($passwordFile[0]);

$invalid_data = false;
$username = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username == "admin" && trim($password) == $syspassword) {
        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $username;
        header('Location: ' . "index.php", true, 303);
        die();
    } else {
        $invalid_data = true;
    }
} else {
    if (isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])) {
        header('Location: ' . "index.php", true, 303);
        die();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>InnoControl - Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link rel="stylesheet" href="css/material.min.css" type="text/css">
    <script defer src="js/material.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="theme-color" content="#40c4ff">
    <style>
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
        }
        .unselectable {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body style="background-color: #333333; height: 100%; min-height:100%; position: relative">
<header>
    <img style="margin: 1%; height: 20%; width: 20%;px" src="images/innotuneweiss.png">
</header>
<div class="mdl-grid">
    <div class="mdl-cell"></div>
    <div class="mdl-cell">
        <div style="margin: 0 auto; align-items: center" class="demo-card-wide mdl-card mdl-shadow--2dp">
            <div class="mdl-card__title">
                <img style="height: 50%;width: 50%" class="unselectable" src="images/loginlogo.png">
                <h2 class="mdl-card__title-text unselectable" style="font-size: 28px;">InnoControl</h2>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mdl-card__supporting-text">
                    <?php
                    if ($invalid_data) {
                        echo "Benutzername/Passwort Kombination inkorrekt!";
                    }
                    ?>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="username"
                               value="<?php echo $username; ?>"
                               id="in_username" autofocus/>
                        <label class="mdl-textfield__label" for="in_username">Benutzername</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="password" name="password" id="in_password" />
                        <label class="mdl-textfield__label" for="in_password">Passwort</label>
                    </div>
                </div>
                <div class="mdl-card__actions mdl-card--border">
                    <input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                           type="submit" value="Anmelden" style="width: 100%">
                </div>
            </form>
        </div>
    </div>
    <div class="mdl-cell"></div>
</div>
<footer style="position:absolute; bottom:0; width:100%">
    <p align="right" style="margin: 1%; color: white"><?php echo $version[0]; ?></p>
</footer>
</body>
</html>