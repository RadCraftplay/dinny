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
        <title>Homepage</title>
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
            <table id="servers">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (isset($servers)) {
                    foreach ($servers as $server) {
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
        <div id="center">
            <ul id="pagination">
                <?php
                    if (!isset($page) || !isset($page_count)) {
                        return;
                    }

                    echo '<li><a href="?p=1">«</a></li>';

                    $min_page = min($page_count - 5, max($page - 2, 1));
                    $max_page = min($min_page + 4, $page_count);
                    for ($i = $min_page; $i <= $max_page; $i++) {
                        echo sprintf('<li><a href="?p=%d">%d</a></li>', $i, $i);
                    }

                    echo sprintf('<li><a href="?p=%d">»</a></li>', $page_count);
                ?>
            </ul>
        </div>
    </body>
</html>