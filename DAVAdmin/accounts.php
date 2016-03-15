<!DOCTYPE html>
<html>
	<head>
		<title>Account List</title>
	</head>
	<body>
		<table>
<?php
try {
	$dbh = new PDO("mysql:host=localhost;dbname=dav", "dav", "plantronics");
} catch (PDOException $e) {
	echo $e->getMessage();
}
$sth = $dbh->query('SELECT `username`, `password` FROM `users` ORDER BY `username`');
$sth->setFetchMode(PDO::FETCH_OBJ);
while($row = $sth->fetch()) {
	echo "<tr>";
	echo "<td>".$row->username."</td>";
	echo "<td>".$row->password."</td>";
	echo "</tr>";
}

$dbh = null;
?>
			
		</table>
		<form action="create.php" method="post">
			<label for="username">Username</label>
			<input type="text" name="usernmae" id="username"><br>
			
			<label for="password">Password</label>
			<input type="password" name="password" id="password"><br>
			<label for="password2">Password (reenter)</label>
			<input type="password" name="password2" id="password2"><br>
			
			<input type="submit">
		</form>
	</body>
</html>