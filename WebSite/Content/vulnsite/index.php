<?php
include("settings.php");
include("header.html");

function anti_ci_filter($input) {
        $result = $input;

        // Mess with Burp Scanner. It's trying CI with ping and echo
        //$result = str_replace('ping', '', $result);
        //$result = str_replace('echo', '', $result);

        $result = str_replace(';', '#', $result); // avoid command injection 1/3
        $result = str_replace('|', '#', $result); // avoid command injection 2/3
        $result = str_replace('`', '#', $result); // avoid command injection 3/3

        $result = str_replace('..', '.', $result); // avoid path traversal 1/2
        $result = str_replace('/', '', $result); // avoid path traversal 2/2

        return $result;
}

if (isset($_GET['doc'])) {
	// You could try to safely read a file, but instead you want to execute a command. SMART.
	$cmd = "cat " . anti_ci_filter($_GET['doc']);
	echo shell_exec($cmd);
} else {
?>

<img id="badmonkey" src="badmonkey.jpg" alt="Bad Monkey">
<h1>&larr; Angry Monkey</h1>
<br>
<h1>&rarr; Learn about us <a href="?doc=us.txt">here</a></h1>

<?php
}
?>

<?php
include("footer.html");
?>
