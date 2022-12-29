<!DOCTYPE html>
<html>

<head>
    <title>
        <?php
        if (isset($server)) {
            echo $server->getTitle();
            echo " - Dinny";
        }
        ?>
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css" />
    <link rel="Stylesheet" type="text/css" href="public/css/server.css" />
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
        <div id="center">
            <div class="section">
                <div class="section-name">
                    <?php
                    if (isset($server)) {
                        echo $server->getTitle();
                    }
                    ?>
                </div>
                <hr />
                <div id="server-info-container">
                    <div id="info-text-container">
                        <div class="info-text-element">
                            <div>Service:</div>
                            <div><a href="#todo">TODO</a></div>
                        </div>
                        <div class="info-text-element">
                            <div>Submitter:</div>
                            <?php
                            if (isset($submitter)) {
                                echo sprintf('<div><a href="/user?id=%s">%s</a></div>',
                                    $submitter->getUserId(),
                                    $submitter->getUsername());
                            } else {
                                echo "Anonymous";
                            }
                            ?>

                        </div>
                    </div>

                    <div class="info-server-url-element">
                        <label id="address-label">Address:</label>
                        <div id="server-address-block">
                            <input value="<?php
                                if (isset($server)) {
                                    echo $server->getAddress();
                                }
                                ?>" readonly />
                            <button class="hilighted">
                                <img src="public/img/svg/copy.svg" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-name-small">
                    Description
                </div>
                <hr />
                <div id="server-description">
                    <p>
                        <?php
                        if (isset($server)) {
                            echo str_replace("\n", "<br>",
                                $server->getDescription());
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>