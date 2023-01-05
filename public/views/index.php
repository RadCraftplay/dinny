<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Homepage</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
        <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
        <link rel="Stylesheet" type="text/css" href="public/css/index.css"/>
        <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">
    </head>
    <body>
    <?php include 'common/navbar.php'?>
        <div id="container">
            <?php
            if (isset($servers) && isset($service_types)) {
                printTableIfServersProvidedOrDefault(
                        $servers,
                        $service_types,
                        "No servers posted yet"
);
            }
            ?>
        </div>
        <div id="center">
            <ul id="pagination">
                <?php
                    const pagination_width = 6;

                    if (!isset($page) || !isset($page_count)) {
                        return;
                    }

                    echo '<li><a href="?p=1">«</a></li>';
                    echo sprintf(
                            '<li><a href="?p=%d"><</a></li>',
                            max(1, $page - 1));

                    $min_page = min(max(1, $page_count - pagination_width), max($page - (pagination_width / 2), 1));
                    $max_page = min($min_page + pagination_width - 1, $page_count);
                    for ($i = $min_page; $i <= $max_page; $i++) {
                        if ($i == $page) {
                            echo sprintf('<li><a href="?p=%d" class="current-page">%d</a></li>', $i, $i);
                        } else {
                            echo sprintf('<li><a href="?p=%d">%d</a></li>', $i, $i);
                        }
                    }

                    echo sprintf(
                            '<li><a href="?p=%d">></a></li>',
                            min($page + 1, $page_count));
                    echo sprintf('<li><a href="?p=%d">»</a></li>', $page_count);
                ?>
            </ul>
        </div>
    </body>
</html>