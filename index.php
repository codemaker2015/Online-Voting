
<?php
 
require('qs_connection.php');
require_once('admin_users.php');
require_once('qs_functions.php');
$
$err_string = "";

@session_start();
$_SESSION["Admin_UserLogon"] = ""
?>
<HTML>

<HEAD>

<Title>Welcome to the online voting system</Title>
<meta http-equiv="" content="3" > 

<script type="text/javascript" src="PHP/js/yahoo-min.js" ></script>
<script type="text/javascript" src="PHP/js/dom-min.js" ></script>
<script type="text/javascript" src="PHP/js/event-min.js" ></script>

<script type="text/javascript">

   YAHOO.util.Event.onDOMReady( function() { qsPageOnLoadController(); } );  

</script>


	<link rel="stylesheet" type="text/css" href="PHP/css/ContentLayout.css"></link>


<script type="text/javascript">

var qsPageItemsCount = 3
var _Studid                                   = 0;
var _Password                                 = 1;
var _Leve                                     = 2;

// Declare Fields Prompts
var fieldPrompts = [];
fieldPrompts[_Studid] = "Studid";
fieldPrompts[_Password] = "Password";
fieldPrompts[_Leve] = "Leve";

// Declare Fields Technical Names
var fieldTechNames = [];
fieldTechNames[_Studid] = "Studid";
fieldTechNames[_Password] = "Password";
fieldTechNames[_Leve] = "Leve";

// This function dynamically assigns element 'ID' attributes to all relevant elements
function qsAssignElementIDs() {

  // STEP 1: Assign an ID to all field PROMPTS (TD captions)
  // Scan all table TD tags for those that match field prompts
  var TDs = document.getElementsByTagName("td");
  for (var i=0; i < TDs.length; i++) {
    var element = TDs[i];
    if (element.className == "ThRows" || element.className == "TrOdd") {
      for (var f=0; f < qsPageItemsCount; f++) {
        if (element.innerHTML == fieldPrompts[f]) {
            element.id = fieldTechNames[f] + "_caption_cell";
          element.innerHTML = "<div id='" + fieldTechNames[f] + "_caption_div'>" + element.innerHTML + "</div>";
        }
      }
    }
  }

  // STEP 2: Assign an ID to all Input controls on the form
  document.getElementsByName("4")[0].id = fieldTechNames[_Studid];
  document.getElementsByName("5")[0].id = fieldTechNames[_Password];
}

function qsPageItemsAbstraction() {
  qs_form                                  = document.getElementsByName("qs_login_form")[0];   //Define Form Object by Name.



}

</script>



<script type="text/javascript">

// This function dynamically assigns custom events
// to page item controls on this page
function qsAssignPageItemEvents() {
}

</script>





<link href="admin/candidate.css" rel="stylesheet" type="text/css">

</style>
 
<script>

function usrEvent__Login_To_Admin__onload() { }
function usrEvent__Login_To_Admin__onunload() { }
function usrEvent__Login_To_Admin__onresize() { }

// This function controls the OnUnload event dispatching
function qsPageOnUnloadController() { }

// This function controls the OnResize event dispatching
function qsPageOnResizeController() {   
   var lastResult = false                              
   return true;                                        
}                                                      

// This function controls the OnLoad events dispatching
function qsPageOnLoadController() {   
   var lastResult = false                              

   // Invoke the technical field names abstraction initialization
   qsPageItemsAbstraction();

   // Invoke the Element IDs assignment function
   qsAssignElementIDs();

   // Invoke the Page Items custom events assignments
   qsAssignPageItemEvents();
   // Assign Event Handlers for page-level events
   YAHOO.util.Event.addListener(window, "beforeunload", qsPageOnUnloadController);
   YAHOO.util.Event.addListener(window, "resize", qsPageOnResizeController);
   // Set focus on first enterable page item available
  document.getElementsByName("User")[0].focus(); 
   return true;                                        
}                                                      

function usrEvent__Login_To_Admin__onsubmit(frm) { }
function usrEvent__Login_To_Admin__onreset() { }

// This function controls the OnSubmit event dispatching
function qsFormOnSubmitController(frm) {                 
   var lastResult = false                              
   return true;                                        
}                                                      

// This function controls the OnReset event dispatching
function qsPageOnResetController() {   
   var lastResult = false                              
   return true;                                        
}                                                      


</script>

<style type="text/css">

</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></HEAD>
<style type="text/css">

</style><BODY>
<Center>
  <center><img src="pictures/Election 2k10.jpg" width="1000" height="200">
 </center>

<table width="1003" border="0" align="center">
  <tr>
    <td width="634"><div align="center"><font size="5"><span class="style12">To Start Voting</span> <a href="user_login.php"><strong>Click Here</strong></a></font></div>
    <Table width="622" height="85" border="1">
        <?php 

$sqlcandi = mysql_query("select * from position order by IDNo");
while($rowcandi=mysql_fetch_array($sqlcandi))
{
?>
        <tr>
          <td colspan="3" align="center" class="ThRows style1"><span class="style6"><?php echo $rowcandi['position']; ?></span> </td>
        </tr>
        <?php
  $sqlvote6 = mysql_query("select * from candidate where position = '".$rowcandi['position']."' order by position, votecount desc");
	$stud_query=mysql_query("SELECT * FROM students");
	$tot_stud = mysql_num_rows($stud_query);
while($row16 = mysql_fetch_array($sqlvote6)) {
$percent = ($row16[votecount]/$tot_stud)*100;

?>
        <tr>
          <td width="249" class="TrOdd"><span class="style6"><?php echo $row16['name']; ?></span></td>
          <td width="204" class="TrOdd"><span class="style6"><?php echo $row16['votecount']." - ".number_format ($percent, 2, '.', ' ')." %"; ?></span></td>
          <td width="147" class="TrOdd"><TABLE bgColor=red
 height=20 width=<?php echo number_format ($percent, 2, '.', ' ')." %";  ?>
 cellSpacing=0 cellPadding=0 border= 0>
              <TR>
                <TD></TD>
              </TR>
          </TABLE></td>
        </tr>
        <?php
}
?>
        <tr>
          <td colspan="3" align="center" class="TrHover style6">&nbsp;</td>
        </tr>
        <?php
}
?>
      </Table></td>
    <td width="359" valign="top" nowrap><p align="center"><img src="pictures/emc.gif" width="375" height="260"></p>
      </td>
  </tr>
</table>
<br><br>
<div id="Footer" align="center"> 
 <p>&copy; <?php echo date('Y');?>.All rights reserved E-voting system</p>
        <p>Designed & Developed by  <b>Harjeet kaur</b></p>
		
  </div>
</Center>


</BODY>

</HTML>

