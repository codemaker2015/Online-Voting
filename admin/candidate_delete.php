
<?php
 
require('qs_connection.php');
require('qs_functions.php');
@session_start();
$err_string = "";
$quotechar = "`";
$quotedate = "'";
$sql = "";
$sql_ext = "";
$fields = array();
$fields[0] = "candidate.id";
$fields[1] = "candidate.`position`";
$fields[2] = "candidate.name";
$fields[3] = "candidate.platform";
$fields[4] = "candidate.picture";
$fields[5] = "candidate.votecount";
$fields[6] = "candidate.sy";
$fields = array();
$fields[0] = "candidate.id";
$fields[1] = "candidate.`position`";
$fields[2] = "candidate.name";
$fields[3] = "candidate.platform";
$fields[4] = "candidate.picture";
$fields[5] = "candidate.votecount";
$fields[6] = "candidate.sy";

$fieldcons = array();
$fieldcons[0] = "candidate.id";
$fieldcons[1] = "candidate.`position`";
$fieldcons[2] = "candidate.name";
$fieldcons[3] = "candidate.platform";
$fieldcons[4] = "candidate.picture";
$fieldcons[5] = "candidate.votecount";
$fieldcons[6] = "candidate.sy";

$sql .= " Select\n";
    $sql .= "     candidate.`id`,\n";
    $sql .= "     candidate.`position`,\n";
    $sql .= "     candidate.`name`,\n";
    $sql .= "     candidate.`platform`,\n";
    $sql .= "     candidate.`picture`,\n";
    $sql .= "     candidate.`votecount`,\n";
    $sql .= "     candidate.`sy`\n";
    $sql .= " From\n";
    $sql .= "     candidate   candidate\n";

if (strpos(strtoupper($sql), " WHERE ")) {
   $sqltemp = $sql . " AND (1=0) ";
}else{
   $sqltemp = $sql . " Where (1=0) ";
}
//Field Related Declarations
$req_Id              = "del_fd0";
$req_Position        = "del_fd1";
$req_Name            = "del_fd2";
$req_Platform        = "del_fd3";
$req_Picture         = "del_fd4";
$req_Votecount       = "del_fd5";
$req_Sy              = "del_fd6";

//Assign Recordset Field Index
$rs_idx_id           = 0;
$rs_idx_position     = 1;
$rs_idx_name         = 2;
$rs_idx_platform     = 3;
$rs_idx_picture      = 4;
$rs_idx_votecount    = 5;
$rs_idx_sy           = 6;

$result = mysql_query($sqltemp . " " . $sql_ext . " limit 0,1")
          or die("Invalid query");
if (!$result){
}
$qry_string = "";
$value_sql = "";
$currentrow_sql = "";
$hidden_tag = "";
$hiddenrow_tag = "";
$i = 0;
while ($i < mysql_num_fields($result)) {
    $meta = mysql_fetch_field($result);
    $field_name = $meta->name;
    $field_type = $meta->type;
    $type_field = "";
    $type_field = returntype($field_type);
    $quotedata = "";
    switch ($type_field) {
      case "type_datetime": $quotedata = $quotedate; break;
      case "type_string": $quotedata = "'"; break;
      case "type_integer": $quotedata = ""; break;
      case "type_unknown": $quotedata = "'"; break;
      default: $quotedata = "'";
    } 
    if (qsrequest("currentrow_fd" .$i) != "") {
        if ($currentrow_sql == "") {
            $currentrow_sql  = $fields[$i] . " = " . $quotedata . qsrequest("currentrow_fd" . $i) . $quotedata . "";
        } else {
            $currentrow_sql .= " and " . $fields[$i] . " = " . $quotedata . qsrequest("currentrow_fd" . $i) . $quotedata . "";
        }
        $hiddenrow_tag .= "<input type=\"hidden\" name=\"currentrow_fd" . $i . "\" value=\"" . qsrequest("currentrow_fd" . $i) . "\">\n";
    }
    $i++;
}
//Create recordset data 
    if ($result > 0) {mysql_free_result($result);}
    $sql = "";
$sql .= " Select\n";
    $sql .= "     candidate.`id`,\n";
    $sql .= "     candidate.`position`,\n";
    $sql .= "     candidate.`name`,\n";
    $sql .= "     candidate.`platform`,\n";
    $sql .= "     candidate.`picture`,\n";
    $sql .= "     candidate.`votecount`,\n";
    $sql .= "     candidate.`sy`\n";
    $sql .= " From\n";
    $sql .= "     candidate   candidate\n";
    $sql .= " where ".$currentrow_sql ;
   $result =  mysql_query($sql) or die("Invalid query");
    if (!$result){
  }
 $row = mysql_fetch_array($result);

//Check form submit 
if (isset($_POST["act"])) {
 $sql  = "";
    $sql  = "delete from " . $quotechar. mysql_field_table($result,0) . $quotechar;
    $sql .= " where ";
    $sql .= qsreplace_backslashes(stripslashes($currentrow_sql));
   $submiturl = "./candidate.php?";
    if ($result > 0) {mysql_free_result($result);}
    if ($err_string == "") {
    if (!$result = @mysql_query($sql)){
        $err_string .= "<strong>Error:</strong>while updating<br>" . mysql_error();
  } else { 
  }
    if ($err_string == "") {
        if ($qry_string != "") {
            $URL= $submiturl . "&" . $qry_string;
        } else {
            $URL= $submiturl;
        }
            header ("Location: $URL");
            exit;
        }
    }
} //end if act
?>
<HTML>

<HEAD>

<Title>Delete Candidate</Title>

<link rel="stylesheet" type="text/css" href="candidate_delete.css">



   
<script type="text/javascript" src="./js/yahoo-min.js" ></script>
<script type="text/javascript" src="./js/dom-min.js" ></script>
<script type="text/javascript" src="./js/event-min.js" ></script>

<script type="text/javascript">

  
  YAHOO.util.Event.onDOMReady( function() { qsPageOnLoadController(); } );  

</script>


	<link rel="stylesheet" type="text/css" href="./css/ContentLayout.css"></link>


<!-- END OF STD-Loader.txt -->


<script type="text/javascript">


var qsPageItemsCount = 7
var _Id                                       = 0;
var _Position                                 = 1;
var _Name                                     = 2;
var _Platform                                 = 3;
var _Picture                                  = 4;
var _Votecount                                = 5;
var _Sy                                       = 6;

// Declare Fields Prompts
var fieldPrompts = [];
fieldPrompts[_Id] = "Id";
fieldPrompts[_Position] = "Position";
fieldPrompts[_Name] = "Name";
fieldPrompts[_Platform] = "Platform";
fieldPrompts[_Picture] = "Picture";
fieldPrompts[_Votecount] = "Votecount";
fieldPrompts[_Sy] = "Sy";

// Declare Fields Technical Names
var fieldTechNames = [];
fieldTechNames[_Id] = "Id";
fieldTechNames[_Position] = "Position";
fieldTechNames[_Name] = "Name";
fieldTechNames[_Platform] = "Platform";
fieldTechNames[_Picture] = "Picture";
fieldTechNames[_Votecount] = "Votecount";
fieldTechNames[_Sy] = "Sy";

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
}

 
function qsPageItemsAbstraction() {
  qs_form                                  = document.getElementsByName("qs_delete_form")[0];   //Define Form Object by Name.







}

</script>



<script type="text/javascript">


function qsAssignPageItemEvents() {
}

</script>




<script>


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

   return true;                                        
}                                                      






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


<meta name="generator" content="dbQwikSite Ecommerce"><meta name="dbQwikSitePE" content="QSFREEPE">

</HEAD>


<BODY>

<Center>
<center><hr /><font size="5">
Delete Candidate
</font><hr /></center><br>


<A NAME=top></A>

<table id="QS_Content_Layout_1_Table">
  <tr id="QS_Content_Layout_1_TopRow">
    <td id="QS_Content_Layout_1_NorthWest">
            <div id="QS_Content_Layout_1_NorthWestDiv">

        </div>
    </td>
    <td id="QS_Content_Layout_1_North">
            <div id="QS_Content_Layout_1_NorthDiv">

        </div>
    </td>
    <td id="QS_Content_Layout_1_NorthEast">
            <div id="QS_Content_Layout_1_NorthEastDiv">

        </div>
    </td>
  </tr>
  <tr id="QS_Content_Layout_1_MiddleRow">
    <td id="QS_Content_Layout_1_West">
            <div id="QS_Content_Layout_1_WestDiv">

        </div>
    </td>
    <td id="QS_Content_Layout_1_Center">
            <div id="QS_Content_Layout_1_CenterDiv">



<Form name="qs_delete_form" method="post" action="./candidate_delete.php" onSubmit="return qsFormOnSubmitController(this)"  onReset="return qsPageOnResetController(this)" >
<?php
print $hidden_tag;
print $hiddenrow_tag;
?>

<Table Border="0" Cellpadding="2" Cellspacing="1" BgColor="#D4D4D4">

<?php
$css_class = "\"TrOdd\"";
?>
<tr>
<td colspan="2" class="ThRows">Delete Candidate</td>
</tr>
<?php
if ($err_string != "") {
    print "<tr>";
    print "<td class=\"ThRows\"><Strong>Error:</Strong></td>";
    print "<td class=" . $css_class . " align=Default>" . $err_string . "</td>";
    print "</tr>";
}
?>
<tr>
<td class="ThRows">Id</td>
<?php
$itemvalue = "" . number_format($row[0],0,".",",") . "";
if ($itemvalue == "") {
    $itemvalue = "&nbsp;";
}

    $cellvalue =  $itemvalue;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Position</td>
<?php
$itemvalue = "" . $row[1] . "";
if ($itemvalue == "") {
    $itemvalue = "&nbsp;";
}

    $cellvalue =  $itemvalue;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Name</td>
<?php
$itemvalue = "" . $row[2] . "";
if ($itemvalue == "") {
    $itemvalue = "&nbsp;";
}

    $cellvalue =  $itemvalue;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Platform</td>
<?php
$itemvalue = "" . $row[3] . "";
if ($itemvalue == "") {
    $itemvalue = "&nbsp;";
}

    $cellvalue =  $itemvalue;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Picture</td>
<?php
$itemvalue = "" . $row[4] . "";
if ($itemvalue == "") {
    $itemvalue = "&nbsp;";
}

    $cellvalue =  $itemvalue;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }
  print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Votecount</td>
<?php
$itemvalue = "" . number_format($row[5],0,".",",") . "";
if ($itemvalue == "") {
    $itemvalue = "&nbsp;";
}

    $cellvalue =  $itemvalue;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Sy</td>
<?php
$itemvalue = "" . $row[6] . "";
if ($itemvalue == "") {
    $itemvalue = "&nbsp;";
}

    $cellvalue =  $itemvalue;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<?php
#----get back url page----
  $backurl = "./candidate.php?";
?>
<tr>
<td class="ThRows">&nbsp;</td>
<td class="TrOdd" align=Default>
<input type="hidden" name="act" value="n">
<input type="button" name="QS_Back" value="Back" OnClick="javascript:window.location='<?php print $backurl; ?>'">&nbsp;&nbsp;
<input type="submit" name="QS_Submit" value="Delete">&nbsp;&nbsp;
</td>
</tr>
</Table><br>


</Form>
<?php
if ($result > 0) {mysql_free_result($result);}
if ($link > 0) {mysql_close($link);}
?>

        </div>
    </td>
    <td id="QS_Content_Layout_1_East">
            <div id="QS_Content_Layout_1_EastDiv">

        </div>
    </td>
  </tr>
  <tr id="QS_Content_Layout_1_BottomRow">
    <td id="QS_Content_Layout_1_SouthWest">
            <div id="QS_Content_Layout_1_SouthWestDiv">

        </div>
    </td>
    <td id="QS_Content_Layout_1_South">
            <div id="QS_Content_Layout_1_SouthDiv">

        </div>
    </td>
    <td id="QS_Content_Layout_1_SouthEast">
            <div id="QS_Content_Layout_1_SouthEastDiv">

        </div>
    </td>
  </tr>
</table>

<A NAME=bottom></A>

</Center>



</BODY>
<div id="Footer" align="center"> 
 <p>&copy; <?php echo date('Y');?>.All rights reserved E-voting system</p>
        <p>Designed & Developed by  <b>Harjeet kaur</b></p>
		
  </div>
</HTML>

