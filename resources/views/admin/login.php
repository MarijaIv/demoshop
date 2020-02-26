<html lang="en">
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>
<div>
    <h1>Welcome</h1>
    <p>To demo shop administration!</p>
</div>
<div>
    <form action="/login.php" method="post">
        <div class="row">
            <div class="column">
                <label for="user" class="user-pass">Username: </label>
                <label for="pass" class="user-pass">Password: </label>
            </div>
            <div class="column">
                <input type="text" name="username" id="user" class="fields"/>
                <input type="password" name="password" id="pass" class="fields"/>
            </div>
        </div>
        <div>
            <label for="loggedIn" class="check-name">Keep me logged in
                <input type="checkbox" name="keepLoggedIn" id="loggedIn" class="box"/> </label>
            <input type="submit" value="Log in" class="login"/>
        </div>
    </form>
</div>
</body>

</html>

