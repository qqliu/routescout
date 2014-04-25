<html>
<body>

<form method="get" action="login.php">
<label>Email</label><input type="text" name="email">
<br />
<label>Password</label><input type="text" name="password">
<br />
<input type="submit" name="op" value="Login">
</form>

<form method="get" action="login.php">
<input type="text" name="first_name" placeholder="First Name">
<input type="text" name="last_name" placeholder="Last Name">
<br />
<input type="text" name="email" placeholder="Email">
<br />
<input type="text" name="password" placeholder="password">
<br />
<input type="submit" name="op" value="Register">
</form>

<a href="login.php?op=Logout">Logout</a>

</body>
</html>