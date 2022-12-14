<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
    <link rel="Stylesheet" type="text/css" href="public/css/homepage.css"/>
</head>
<body>
<nav id="navbar">
    <a href="/" id="logo">Dinny</a>
    <a href="/submit_server">Submit</a>
    <a href="/">Browse</a>
    <a href="/about">About</a>
    <div class="break"></div>
    <form action="search" method="POST">
        <div id="search-container">
            <input/>
            <button type="submit">
                <img src="public/img/svg/search.svg" />
            </button>
        </div>
    </form>
    <?php
    if (!array_key_exists("logged_user", $_SESSION)) {
        echo "<a href=\"/login\">Log in</a>";
    } else {
        echo "<a href=\"/logout\">Log out</a>";
    }
    ?>
</nav>
<div id="container">
    <h1>Error</h1>
    <p>
        <?php
        if (isset($message)) {
            echo $message;
        } else {
            echo 'Unknown error occured';
        }
        ?>
    </p>
    <a href="/">Go back to the main page</a>
</div>
</body>
</html>