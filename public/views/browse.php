<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Browse - Dinny</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
    <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">
</head>
<body>
<?php include 'common/navbar.php'?>
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
    <h2>Bookmarked servers</h2>
    <table>
        <thead>
        <tr>
            <th>Category</th>
            <th>Name</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($bookmarked_servers) && isset($service_types)) {
            foreach ($bookmarked_servers as $server) {
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
        if (isset($popular_servers) && isset($service_types)) {
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
                    getServiceTypeIcon($server->getServiceTypeId(), $service_types),
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