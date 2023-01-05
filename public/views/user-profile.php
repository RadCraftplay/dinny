<?php
if (session_status() != PHP_SESSION_ACTIVE){
    session_start();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php
        if (isset($user)) {
            echo sprintf('%s\'s profile - Dinny', $user->getUsername());
        }
        ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
    <link rel="Stylesheet" type="text/css" href="public/css/server-table.css"/>
    <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">
</head>
<body>
<?php include 'common/navbar.php'?>
<div id="container">
    <h1>
        <?php
        if (isset($user)) {
            echo sprintf('<h1>%s\'s profile</h1>', $user->getUsername());
        }
        ?>
    </h1>
    <h2>Submitted servers</h2>
    <table>
        <thead>
        <tr>
            <th>Category</th>
            <th>Name</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($users_servers) && isset($service_types)) {
            foreach ($users_servers as $server) {
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