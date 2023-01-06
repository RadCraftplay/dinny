<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
        <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
        <link rel="Stylesheet" type="text/css" href="public/css/login.css"/>
        <link rel="icon" href="public/img/svg/favicon.svg" type="image/svg+xml">

        <script type="text/javascript" src="public/js/register.js" defer></script>
    </head>
    <body>
    <?php include 'common/navbar.php'?>
        <div id="container">
            <div id="center">
                <form action="register_submit" method="POST">
                    <div id="form-contents">
                        <label>E-mail</label>
                        <input name="email" type="email"/>
                        <label>Username</label>
                        <input name="username" type="text"/>
                        <label>Password</label>
                        <input name="password" type="password"/>
                        <label>Confirm password</label>
                        <input name="password_repeated" type="password"/>
                        <button class="hilighted" type="submit">Register</button>
                        <a href="./login">Already have an account? Log in.</a>
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