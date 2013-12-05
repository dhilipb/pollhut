<?php
echo "<h1>Sorry..</h1>";
echo "<p>Unfortunately, this page does not exist</p>";

$msg = $exc -> getMessage();
if ($msg == "LOGIN_FAILURE") {
	echo "<p>You need to login to do that</p>";
}
echo "<p>".$exc->getMessage()."</p>";

//echo $exc;
/*echo "<p>We have logged this issue and we will be fixing this soon!</p>";
 echo "<p>Please check your link</p>";
 echo "<p>".$exc->getMessage()."</p>";*/
?>