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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css" />
    <link rel="Stylesheet" type="text/css" href="public/css/submit-server.css" />
    <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">
</head>

<body>
<?php include 'common/navbar.php'?>
    <div id="container">
        <form action="post_submission" method="POST">
            <div id="form-contents">
                <?php
                printvarf(
                        '<input type="hidden" name="edited_server_id" value="%s" />',
                        "edited_submission_id",
                        $vars);
                ?>
                <label>Server name</label>
                <?php
                printvarfordefault(
                        '<input name="title" type="text" value="%s"/>',
                        "title",
                        '<input name="title" type="text"/>',
                        $vars);
                printvarf('<div class="error-label">%s</div>', "title_message", $vars);
                ?>
                <label>Service</label>
                <select name="service_type">
                    <?php
                    if (!isset($service_type)) {
                        $service_type = 4;
                    }

                    if (isset($service_types)) {
                        foreach ($service_types as $type) {
                            if ($type->getServiceTypeId() == $service_type) {
                                echo sprintf('<option selected>%s</option>', $type->getServiceName());
                            } else {
                                echo sprintf('<option>%s</option>', $type->getServiceName());
                            }
                        }
                    }
                    ?>
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