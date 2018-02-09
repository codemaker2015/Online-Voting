<?php
@session_start();
$host = "localhost:3306";
$db = "voting";
$user = "root";
$passwd = "";
$link = @mysql_connect($host,$user,$passwd);
$conn = $link;
if ($_SESSION["SkipConnectMySQL"] == "") {
  if(!$link) {
   	print "Could not connect to the MySQL Host<br><br>Message(s):<br>" . mysql_error()  . "<br>";
   	exit ;
  }

  if(!@mysql_select_db($db)) {
	  print "Could not connect to the MySQL Database<br><br>Message(s):<br>" . mysql_error()  . "<br>";
	  exit ;
   }
}
?>
