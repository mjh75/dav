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
$sth = $dbh->query("SELECT
											`users`.`username`,
											`principals`.`uri`,
											`principals`.`email`,
											`principals`.`displayname`
										FROM
											`users`
										INNER JOIN `principals` ON `principals`.`uri` LIKE CONCAT('%', `users`.`username`, '%')
										ORDER BY
											`users`.`username` ASC,
											`principals`.`displayname` DESC,
											`principals`.`uri` DESC");
$sth->setFetchMode(PDO::FETCH_OBJ);
while($row = $sth->fetch()) {
	echo "<tr>";
	echo "<td>".$row->username."</td>";
	echo "<td>".$row->displayname."</td>";
	echo "<td>".$row->email."</td>";
	echo "<td>".$row->uri."</td>";
	echo "</tr>";
}

$dbh = null;
?>
			
		</table>
		<form action="create.php" method="post">
			<label for="username">Username</label>
			<input type="text" name="username" id="username"><br>
			<label for="name">Name</label>
			<input type="text" name="name" id="name"><br>
			<label for="email">Email</label>
			<input type="email" name="email" id="email"><br>
			
			<label for="password">Password</label>
			<input type="password" name="password" id="password"><br>
			<label for="password2">Password (reenter)</label>
			<input type="password" name="password2" id="password2"><br>
			
			<input type="submit">
		</form>
	</body>
</html>