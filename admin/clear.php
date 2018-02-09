<?php

require('qs_connection.php');
require('qs_functions.php');
@session_start();
$sql = mysql_query("update candidate set votecount = '0'");
$sql1 = mysql_query("DELETE FROM  votecount");
if($sql and $sql1)
{
$admin="<meta http-equiv=\"Refresh\" content=\"0;url=./tally.php\">";
echo($admin); 
} else {
echo "mali ako";
}
?>