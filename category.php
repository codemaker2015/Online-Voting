<?php

require('connect.php');
require('functions.php');
@session_start();

if($_SESSION["Admin_UserLogon"] == ""){
		$admin="<meta http-equiv=\"Refresh\" content=\"0;url=./index.php\">";
		echo($admin); 
}else{

$stud = mysql_query("select * from students where studid = '".$_SESSION["Admin_UserLogon"]."' group by studid");
$s = mysql_fetch_array($stud);



/*$_SESSION['cons'] = $s['councilor'];
$_SESSION['may'] = $s['Mayor'];
$_SESSION['vice'] = $s['Vice'];
$_SESSION['gov'] = $s['gov'];
$_SESSION['vgov'] = $s['vgov'];
$_SESSION['congess'] = $s['congres'];
$_SESSION['board'] = $s['board'];
$sql  = mysql_query('select * from position');*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="admin/candidate.css" rel="stylesheet" type="text/css">
<style type="text/css">

</style>
</head>

<body>
<table width="820" height="63" border="0" align="center">
  <tr>
    <th width="2" align="right" valign="top" scope="col">&nbsp;</th>
    <th width="808" scope="col"><div align="left"><img src="pictures/Election 2k10.jpg" alt="Category" width="1008" height="275"></div></th>
  </tr>
</table>
<p>&nbsp;</p>
<table width="845" border="0" align="center">
  
  <tr>
    <th width="128" align="left" scope="col">&nbsp;</th>
    <th height="41" colspan="2" align="left" scope="col"><span class="style1">CSG Categories</span> </th>
  </tr>
 <?php 
  $vote = mysql_query("SELECT position, IDNo FROM position order by IDNo");
		while($me = mysql_fetch_array($vote))
		{
			$voto = mysql_query("SELECT * FROM votecount where StudID='".$_SESSION["Admin_UserLogon"]."' AND Position = '".$me['position']."'");		
			$me2 = mysql_fetch_array($voto);
  ?>
 
  <tr>
    <th scope="col">&nbsp;</th>
    <th width="102" scope="col">&nbsp;</th>
    <th width="601" height="41" align="left" scope="col">
	<?php 
		
		if(mysql_num_rows($voto)> 0  || $me2['Result'] !=0) {
		$_SESSION[$me['position']] = $me2['Result']; 
		echo $me['position'];
		
		} else {
		?>
      <a href="votepage.php?cat=<?php echo $me['position']; ?>"><?php echo $me['position']; ?></a><br>
	  <?php 
	 $_SESSION[$me['position']] = $me2['Result']; 
	  }
	  ?>	</th>
  </tr>
   <?php
  
  
}
   ?>
  <tr>
    <th scope="col">&nbsp;</th>
    <th height="41" colspan="2" scope="col"><a href="index.php">Log - out</a></th>
  </tr>
 
</table>
</body>
<div id="Footer" align="center"> 
 <p>&copy; <?php echo date('Y');?>.All rights reserved E-voting system</p>
        <p>Designed & Developed by  <b>Harjeet kaur</b></p>
		
  </div>
</html>
<?php
}
?>