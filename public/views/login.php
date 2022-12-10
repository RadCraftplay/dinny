<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
        <link rel="Stylesheet" type="text/css" href="public/css/base.css"/>
        <link rel="Stylesheet" type="text/css" href="public/css/login.css"/>
    </head>
    <body>
        <nav>
            <div id="container">
                <a href="#home" id="logo">Dinny</a>
                <ul id="navbar">
                    <li><a href="#news">Submit</a></li>
                    <li><a href="#contact">Browse</a></li>
                    <li><a href="#contact">About</a></li>
                    <li><a href="#about">Log In</a></li>
                </ul>
            </div>
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