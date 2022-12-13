<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
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
        <form action="search" method="POST">
            <div id="search-container">
                <input/>
                <button type="submit">
                    <img src="public/img/svg/search.svg" />
                </button>
            </div>
        </form>
        <a href="/login">Log In</a>
    </nav>
        <div id="container">
            <div id="center">
                <form>
                    <div id="form-contents">
                        <label>E-mail</label>
                        <input type="email"/>
                        <label>Username</label>
                        <input type="text"/>
                        <label>Password</label>
                        <input type="password"/>
                        <label>Repeat password</label>
                        <input type="password"/>
                        <button class="hilighted">Register</button>
                        <a href="./login">Already have an account? Log in.</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>