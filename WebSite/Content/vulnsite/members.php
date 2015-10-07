<?php
include("settings.php");
include("header.html");

?>
	<h1>Members</h1>
	<p>We hope you join us soon!</p>
<?php

$sql = "SELECT * from users;";
$result = mysqli_query($conn, $sql);

while($row = $result->fetch_assoc()){
	$id = $row['id'];
	$username = $row['us3rn4m3'];
	$twitter = $row['twitter'];

?>

	<fieldset>
		<legend><?php echo($username);?></legend>
		<dl>
			<dt>twitter</dt>
			<dd><?php echo($twitter);?></dd>
		</dl>
		<dl>
			<a href="profiles/<?php echo($id . '/' . $username);?>">View Profile</a>
		</dl>
	</fieldset>

<?php
}
?>

<?php
include("footer.html");
?>
