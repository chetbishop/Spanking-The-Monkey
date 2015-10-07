<?php
include("settings.php");
include("header.html");

?>
	<h1>Login</h1>
<?php

function anti_xss_filter($input) {
	$result = $input;

	// Mess with Burp Scanner, replace everything left of '<' with '1337'
	$burp_marker = explode('<', $result)[0];
	$result = str_replace($burp_marker, '1337', $result);

	return $result;
}

if(isset($_POST['username']) && isset($_POST['password'])) {
	
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	
	$sql = "SELECT * from users where us3rn4m3='$username' and PassW0rdColuMn='$password' LIMIT 1;";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);

	if(!$row){
	?>
		<!-- XSS -->
		<div>Nobody here with the username <?php echo anti_xss_filter($_POST['username']); ?></div>
	<?php
	}
	else {
	?>
		<h2><p>You're in!</p><p>DemoGods 0 - Antonio 1</p></h2>
	<?php
	}
} else {
?>
	<form action="login.php" method="post">
		<label>Username</label>
		<input type="text" name="username" autocomplete="off">
		<br>
		<label>Password</label>
		<input type="text" name="password" autocomplete="off">
		<br>
		<input type="submit" value="Login">
	</form>

<?php
}
?>

<?php
include("footer.html");
?>
