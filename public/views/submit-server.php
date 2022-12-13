<!DOCTYPE html>
<html>

<head>
    <title>Submit a server</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="Stylesheet" type="text/css" href="public/css/base.css" />
    <link rel="Stylesheet" type="text/css" href="public/css/submit-server.css" />
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
        <form>
            <div id="form-contents">
                <label>Server name</label>
                <input type="text"/>
                <label>Service</label>
                <select>
                    <option>Discord</option>
                    <option>Mumble</option>
                    <option>TeamSpeak</option>
                    <option>Other</option>
                </select>
                <label>Server address</label>
                <input type="text"/>
                <label>Description</label>
                <textarea></textarea>
                <div class="right">
                    <button class="hilighted">Submit</button>
                </div>
            </div>
    </div>
    </div>
</body>

</html>