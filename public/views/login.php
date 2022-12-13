<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
        <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
        <link rel="Stylesheet" type="text/css" href="public/css/login.css"/>
    </head>
    <body>
        <nav id="navbar">
            <a href="/" id="logo">Dinny</a>
            <a href="/submit_server">Submit</a>
            <a href="/">Browse</a>
            <a href="/about">About</a>
            <div class="break"></div>
            <a href="/login">Log In</a>
        </nav>
        <div id="container">
            <div id="center">
                <form action="login_submit" method="POST">
                    <div id="form-contents">
                        <label>Username</label>
                        <input name="username" type="text"/>
                        <label>Password</label>
                        <input name="password" type="password"/>
                        <button class="hilighted">Login</button>
                        <button type="submit">Register</button>
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