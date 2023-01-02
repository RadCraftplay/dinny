<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

function getServiceTypeIcon(int $serverType) {
    switch ($serverType) {
        case 1:
            return "discord.svg";
        case 2:
            return "teamspeak.svg";
        case 3:
            return "mumble.svg";
        default:
            return "other.svg";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Browse - Dinny</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
    <link rel="Stylesheet" type="text/css" href="public/css/server-table.css"/>
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
    <h1>
        <?php
        if (isset($_SESSION) && array_key_exists("logged_user", $_SESSION)) {
            echo sprintf(
                '<h1>Hello <a href="/user?id=%s">%s</a></h1>',
                $_SESSION["logged_user"]->getUserId(),
                $_SESSION["logged_user"]->getUsername()
            );
        }
        ?>
    </h1>
    <h2>Popular servers (last 31 days)</h2>
    <table>
        <thead>
        <tr>
            <th>Category</th>
            <th>Name</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($popular_servers)) {
            foreach ($popular_servers as $server) {
                echo sprintf("<tr>
                        <td>
                            <a href=\"/server?id=%s\">
                                <img src=\"public/img/svg/server-types/%s\">
                            </a>
                        </td>
                        <td><a class=\"server-entry\" href=\"/server?id=%s\">%s</a></td>
                    </tr>",
                    $server->getSubmissionId(),
                    getServiceTypeIcon($server->getServiceTypeId()),
                    $server->getSubmissionId(),
                    $server->getTitle());
            }
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>