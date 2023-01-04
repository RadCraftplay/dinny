<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
        <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
        <link rel="Stylesheet" type="text/css" href="public/css/login.css"/>
        <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">
    </head>
    <body>
    <?php include 'common/navbar.php'?>
        <div id="container">
            <div id="center">
                <form action="login_submit" method="POST">
                    <div id="form-contents">
                        <label>E-mail</label>
                        <input name="email" type="text"/>
                        <label>Password</label>
                        <input name="password" type="password"/>
                        <button class="hilighted">Login</button>
                        <button onclick="location.href='/register'" type="button">Register</button>
                        <a href="#forget-password">Forgot your password?</a>
                        <div id="messages">
                            <?php
                                if (isset($messages)) {
                                    foreach ($messages as $message) {
                                        echo $message . '<br>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>