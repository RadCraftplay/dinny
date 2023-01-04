<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

function getServiceTypeIcon(int $serverType, array $service_types): string {
    foreach ($service_types as $type) {
        if ($type->getServiceTypeId() == $serverType) {
            return $type->getServiceImageName();
        }
    }

    return "other.svg";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Homepage</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
        <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
        <link rel="Stylesheet" type="text/css" href="public/css/index.css"/>
        <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">
    </head>
    <body>
    <?php include 'common/navbar.php'?>
        <div id="container">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (isset($servers) && isset($service_types)) {
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
                            getServiceTypeIcon($server->getServiceTypeId(), $service_types),
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