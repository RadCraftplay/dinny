<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

$vars = get_defined_vars();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Submit a server</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css" />
    <link rel="Stylesheet" type="text/css" href="public/css/submit-server.css" />
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
        <form action="post_submission" method="POST">
            <div id="form-contents">
                <label>Server name</label>

                <?php
                printvarfordefault(
                        '<input name="title" type="text" value="%s"/>',
                        "address",
                        '<input name="title" type="text"/>',
                        $vars);
                printvarf('<div class="error-label">%s</div>', "title_message", $vars);
                ?>
                <label>Service</label>
                <select name="service_type">
                    <option>Discord</option>
                    <option>Mumble</option>
                    <option>TeamSpeak</option>
                    <option>Other</option>
                </select>
                <?php printvarf('<div class="error-label">%s</div>', "service_type_message", $vars);?>
                <label>Server address</label>
                <?php
                printvarfordefault(
                        '<input name="address" type="text" value="%s"/>',
                        "address",
                        '<input name="address" type="text"/>',
                        $vars);
                printvarf('<div class="error-label">%s</div>', "address_message", $vars);
                ?>
                <label>Description</label>
                <textarea name="description"><?php printvarf('%s', "description", $vars); ?></textarea>
                <?php printvarf('<div class="error-label">%s</div>', "description_message", $vars);?>
                <div class="right">
                    <button class="hilighted" type="submit">Submit</button>
                </div>
            </div>
    </div>
</body>

</html>