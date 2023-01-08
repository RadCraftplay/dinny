<!DOCTYPE html>
<?php
function printIfTrue(string $to_print, string $varname, array $vars) {
    if ($vars[$varname]) {
        echo $to_print;
    }
}
?>
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
    <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">

    <script type="text/javascript" src="public/js/server.js"></script>
</head>

<body>
<?php include 'common/navbar.php'?>
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
                            <?php
                            if (isset($server_type)) {
                                echo sprintf(
                                        '<div><a href="#%d">%s</a></div>',
                                        $server_type->getServiceTypeId(),
                                        $server_type->getServiceName());
                            }
                            ?>

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
                        <div class="info-text-element">
                            <div>Submitted:</div>
                            <?php
                            if (isset($server)) {
                                echo sprintf('<div>%s</div>', date("Y-m-d H:i:s", $server->getSubmissionDate()));
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
                                ?>" id="server-address-input" readonly />
                            <button class="hilighted" onclick="copyToClipboard()">
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

        <div id="entry-management">
            <?php
                if (!isset($server)) {
                    return;
                }
                printIfTrue(
                    (isset($bookmarked) && $bookmarked)
                        ? sprintf(
                                '<button onclick="location.href=\'/unbookmark_server?id=%s\'"">Remove from bookmarks</button>',
                                $server->getSubmissionId())
                        : sprintf(
                                '<button class="hilighted" onclick="location.href=\'/bookmark_server?id=%s\'"">Bookmark server</button>',
                                $server->getSubmissionId()),
                    'can_save',
                    $vars
                );
                printIfTrue(
                    sprintf(
                            '<button onclick="location.href=\'/edit_server?id=%s\'"">Edit submission</button>',
                            $server->getSubmissionId()
                    ),
                    'can_edit',
                    $vars
                );
                printIfTrue(
                        sprintf(
                                '<button onclick="location.href=\'/delete_server?id=%s\'" class="attention">Delete submission</button>',
                            $server->getSubmissionId()
                        ),
                        'can_remove',
                        $vars
                );
            ?>
        </div>
    </div>
</body>

</html>