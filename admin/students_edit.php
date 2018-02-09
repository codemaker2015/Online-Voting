<?php

require('qs_connection.php');
require('qs_functions.php');
$row = "";
@session_start();
$err_string = "";
$quotechar = "`";
$quotedate = "'";
$sql = "";
$sql_ext = "";
$fields = array();
$fields[0] = "students.studid";
$fields[1] = "students.name";
$fields[2] = "students.course";
$fields[3] = "students.`year`";
$fields[4] = "students.sec";
$fields[5] = "students.password";
$fields[6] = "students.sy";
$formatdate = array();
$formatdate[0] = "";
$formatdate[1] = "";
$formatdate[2] = "";
$formatdate[3] = "";
$formatdate[4] = "";
$formatdate[5] = "";
$formatdate[6] = "";
$seperatedate = array();
$seperatedate[0] = " ";
$seperatedate[1] = " ";
$seperatedate[2] = " ";
$seperatedate[3] = " ";
$seperatedate[4] = " ";
$seperatedate[5] = " ";
$seperatedate[6] = " ";
$fields = array();
$fields[0] = "students.studid";
$fields[1] = "students.name";
$fields[2] = "students.course";
$fields[3] = "students.`year`";
$fields[4] = "students.sec";
$fields[5] = "students.password";
$fields[6] = "students.sy";

$fieldcons = array();
$fieldcons[0] = "students.studid";
$fieldcons[1] = "students.name";
$fieldcons[2] = "students.course";
$fieldcons[3] = "students.`year`";
$fieldcons[4] = "students.sec";
$fieldcons[5] = "students.password";
$fieldcons[6] = "students.sy";

$isEditables = array();
$isEditables[0] = 1;
$isEditables[1] = 1;
$isEditables[2] = 1;
$isEditables[3] = 1;
$isEditables[4] = 1;
$isEditables[5] = 1;
$isEditables[6] = 1;

$sql .= " Select\n";
    $sql .= "     students.`studid`,\n";
    $sql .= "     students.`name`,\n";
    $sql .= "     students.`course`,\n";
    $sql .= "     students.`year`,\n";
    $sql .= "     students.`sec`,\n";
    $sql .= "     students.`password`,\n";
    $sql .= "     students.`sy`\n";
    $sql .= " From\n";
    $sql .= "     students   students\n";

//Field Related Declarations
$req_Studid          = "edit_fd0";
$req_Name            = "edit_fd1";
$req_Course          = "edit_fd2";
$req_Year            = "edit_fd3";
$req_Sec             = "edit_fd4";
$req_Password        = "edit_fd5";
$req_Sy              = "edit_fd6";

//Assign Recordset Field Index
$rs_idx_studid       = 0;
$rs_idx_name         = 1;
$rs_idx_course       = 2;
$rs_idx_year         = 3;
$rs_idx_sec          = 4;
$rs_idx_password     = 5;
$rs_idx_sy           = 6;

if (strpos(strtoupper($sql), " WHERE ")) {
   $sqltemp = $sql . " AND (1=0) ";
}else{
   $sqltemp = $sql . " Where (1=0) ";
}
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
    if (qsvalidRequest("search_fd" .$i)) {
        if ($qry_string == "") {
            $qry_string = "search_fd" . $i . "=" . qsrequest("search_fd" . $i);
        } else {
            $qry_string .= "&search_fd" .$i . "=" . qsrequest("search_fd" . $i);
        }
        $hidden_tag .= "<input type=\"hidden\" name=\"search_fd" . $i . "\" value=\"" . qsrequest("search_fd" . $i) . "\">\n";
        if ($qry_string == "") {
            $qry_string = "multisearch_fd" . $i . "=" . urlencode(stripslashes(qsrequest("multisearch_fd" . $i)));
        } else {
            $qry_string .= "&multisearch_fd" .$i . "=" . urlencode(stripslashes(qsrequest("multisearch_fd" . $i)));
        }
        $hidden_tag .= "<input type=\"hidden\" name=\"multisearch_fd" .$i . "\" value=\"" . qsreplace_html_quote(stripslashes(qsrequest("multisearch_fd" . $i))) . "\">\n";
    }
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
    if ($meta) {
        if (qsrequest("currentrow_fd" .$i) != "") {
            if ($currentrow_sql == "") {
                $currentrow_sql  = $fields[$i] . " = " . $quotedata . ereg_replace("'","''",stripslashes(qsrequest("currentrow_fd" . $i))) . $quotedata;
            } else {
                $currentrow_sql .= " and " .$fields[$i] . " = " . $quotedata . ereg_replace("'","''",stripslashes(qsrequest("currentrow_fd" . $i))) . $quotedata;
            }
            $hiddenrow_tag .= "<input type=\"hidden\" name=\"currentrow_fd" . $i . "\" value=\"" . qsreplace_html_quote(stripslashes(qsrequest("currentrow_fd" . $i))) . "\">\n";
        }
      if($isEditables[$i])
      {
        if ($type_field == "type_datetime") {
            if (qsvalidRequest("edit_fd" . $i)) {
                $idata = qsrequest("edit_fd" . $i);

                $value_sql .= "," . $quotechar . $field_name . $quotechar . " = " . $quotedate . qsconvertdate2ansi($idata,$formatdate[$i],$seperatedate[$i]) . $quotedate;
            }
            else if($isEditables[$i] != 2) {
		             $value_sql .= "," . $quotechar . $field_name . $quotechar . " = null"; 
	           }
        } elseif ($type_field == "type_integer") {
            if (qsvalidRequest("edit_fd" . $i)) {
                $idata = qsrequest("edit_fd" . $i);
                $idata = QSConvert2EngNumber($idata); 

                if (is_numeric($idata)) {
                        $value_sql  .= "," . $quotechar . $field_name . $quotechar . " = " . $idata;
                } else {
                    $err_string .= "<strong>Error:</strong>while updating<strong>" . $field_name . "</strong>.<br>";
                    $err_string .= "Description: Type mismatch.<br>";
                }
            }
			       else if($isEditables[$i] != 2) {
		             $value_sql .= "," . $quotechar . $field_name . $quotechar . " = null"; 
	           }
        } elseif ($type_field == "type_string") {
            if (qsvalidRequest("edit_fd" . $i)) {
                $idata = qsrequest("edit_fd" . $i);
             $value_sql  .= "," . $quotechar . $field_name . $quotechar . " = '" . ereg_replace("'","''",stripslashes($idata)) . "'";
            }
			       else if($isEditables[$i] != 2) {
		             $value_sql .= "," . $quotechar . $field_name . $quotechar . " = null"; 
	           }
        } else {
            if (qsvalidRequest("edit_fd" . $i)) {
                $idata = qsrequest("edit_fd" . $i);

                $value_sql  .= "," . $quotechar . $field_name . $quotechar . " = '" . ereg_replace("'","''",stripslashes($idata)) . "'";
            }
			       else if($isEditables[$i] != 2) {
		             $value_sql .= "," . $quotechar . $field_name . $quotechar . " = null"; 
	           }
        }

      }//if ($isEditables[$i])
    }//if ($meta)
$i++;
}
if (isset($_POST["act"])) {
   $ProcessForm  = "Y"; 
  if ($ProcessForm  == "Y") { 
if (($err_string)=="") {
    if (($value_sql)!="") {
        if (substr($value_sql, 0, 1) == ",") {
           $value_sql = substr($value_sql, 1);
        }
       $sql  = "";
        $sql  = "update " . $quotechar . mysql_field_table($result,0) . $quotechar;
        $sql .= " set " . $value_sql;
        $sql .= " where ";
        $sql .= $currentrow_sql;
    if ($result > 0) {mysql_free_result($result);}
      if (!$result = @mysql_query($sql)){
        $err_string .= "<strong>Error:</strong>while updating<br>" . mysql_error();
     } else {
    }
    }
#----get submit url page----
    $submiturl = "./students.php?";
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
  } //end if ProcessForm 
}
else
{
    if ($result > 0) {mysql_free_result($result);}
    $sql = "";
$sql .= " Select\n";
    $sql .= "     students.`studid`,\n";
    $sql .= "     students.`name`,\n";
    $sql .= "     students.`course`,\n";
    $sql .= "     students.`year`,\n";
    $sql .= "     students.`sec`,\n";
    $sql .= "     students.`password`,\n";
    $sql .= "     students.`sy`\n";
    $sql .= " From\n";
    $sql .= "     students   students\n";
  if ($currentrow_sql != "") {
    $sql .= " where ".$currentrow_sql ;
  }
    $result =  mysql_query($sql) or die("Invalid query");
    $row = mysql_fetch_array($result);
}
?>
<HTML>

<HEAD>

<Title>Update Students</Title>

<link rel="stylesheet" type="text/css" href="students_edit.css">
  
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
var _Studid                                   = 0;
var _Name                                     = 1;
var _Course                                   = 2;
var _Year                                     = 3;
var _Sec                                      = 4;
var _Password                                 = 5;
var _Sy                                       = 6;

// Declare Fields Prompts
var fieldPrompts = [];
fieldPrompts[_Studid] = "Studid";
fieldPrompts[_Name] = "Name";
fieldPrompts[_Course] = "Course";
fieldPrompts[_Year] = "Year";
fieldPrompts[_Sec] = "Sec";
fieldPrompts[_Password] = "Password";
fieldPrompts[_Sy] = "Sy";

// Declare Fields Technical Names
var fieldTechNames = [];
fieldTechNames[_Studid] = "Studid";
fieldTechNames[_Name] = "Name";
fieldTechNames[_Course] = "Course";
fieldTechNames[_Year] = "Year";
fieldTechNames[_Sec] = "Sec";
fieldTechNames[_Password] = "Password";
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
  document.getElementsByName("edit_fd0")[0].id = fieldTechNames[_Studid];
  document.getElementsByName("edit_fd1")[0].id = fieldTechNames[_Name];
  document.getElementsByName("edit_fd2")[0].id = fieldTechNames[_Course];
  document.getElementsByName("edit_fd3")[0].id = fieldTechNames[_Year];
  document.getElementsByName("edit_fd4")[0].id = fieldTechNames[_Sec];
  document.getElementsByName("edit_fd5")[0].id = fieldTechNames[_Password];
  document.getElementsByName("edit_fd6")[0].id = fieldTechNames[_Sy];
}


function qsPageItemsAbstraction() {
  qs_form                                  = document.getElementsByName("qs_edit_form")[0];   //Define Form Object by Name.
  pgitm_Studid                             = document.getElementsByName("edit_fd0")[0];
  pgitm_Name                               = document.getElementsByName("edit_fd1")[0];
  pgitm_Course                             = document.getElementsByName("edit_fd2")[0];
  pgitm_Year                               = document.getElementsByName("edit_fd3")[0];
  pgitm_Sec                                = document.getElementsByName("edit_fd4")[0];
  pgitm_Password                           = document.getElementsByName("edit_fd5")[0];
  pgitm_Sy                                 = document.getElementsByName("edit_fd6")[0];
}

</script>



<script type="text/javascript">


function qsAssignPageItemEvents() {
}

</script>






<script>



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
  pgitm_Studid.focus();
   return true;                                        
}                                                      





// This function controls the OnSubmit event dispatching
function qsFormOnSubmitController(frm) {                 
   var lastResult = false                              
   // Call the standard dbQwikSite form validation rules
   lastResult = check(frm);                            
   if (lastResult == false) {                          
      return false;                                    
   }                                                   
   return true;                                        
}                                                      



// This function controls the OnReset event dispatching
function qsPageOnResetController() {   
   var lastResult = false                              
   return true;                                        
}                                                      


</script>

<script language='javascript' src='qwikcalendar.js'></script>
<script language="javascript">
function Trim(s){
    var temp = " ";
    var i = 0;
    while ((temp == " ") && (i <= s.length)) {
        temp = s.charAt(i);
        i++;
    }
    s = s.substring(i - 1, s.length);
    return(s);
}
function check(frm) {
    var szAlert = "Invalid\n";
    var nIndex = 0;
    if (!RequiredField(frm.edit_fd0.value)) {
        nIndex++;
        szAlert += "- " +"'Studid' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd1.value)) {
        nIndex++;
        szAlert += "- " +"'Name' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd2.value)) {
        nIndex++;
        szAlert += "- " +"'Course' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd3.value)) {
        nIndex++;
        szAlert += "- " +"'Year' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd4.value)) {
        nIndex++;
        szAlert += "- " +"'Sec' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd5.value)) {
        nIndex++;
        szAlert += "- " +"'Password' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd6.value)) {
        nIndex++;
        szAlert += "- " +"'Sy' cannot be blank\n";
    }
    if(nIndex > 0) {
       	alert(szAlert) ;
      	return false ;
    }
    return true ;
}
</script>
<script src="validate.js"></script>


<meta name="generator" content="dbQwikSite Ecommerce"><meta name="dbQwikSitePE" content="QSFREEPE">

</HEAD>

<BODY>


<Center>
<center><hr /><font size="5">
Update Students
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


<script>
function getURLParam(strParamName){
var strReturn = "";
var strHref = window.location.href;
if ( strHref.indexOf("?") > -1 ){
  var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
  var aQueryString = strQueryString.split("&");
  for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
    if (
	aQueryString[iParam].indexOf(strParamName + "=") > -1 ){
      var aParam = aQueryString[iParam].split("=");
      strReturn = aParam[1];
      break;
    }
  }
}
return strReturn;
}
</script>


<Form name="qs_edit_form" method="post" action="./students_edit.php" onSubmit="return qsFormOnSubmitController(this)"  onReset="return qsPageOnResetController(this)" >

<?php
print $hidden_tag;
print $hiddenrow_tag;
?>

<Table Border="0" Cellpadding="2" Cellspacing="1" BgColor="#D4D4D4">

<?php
$css_class = "\"TrOdd\"";
?>
<tr>
<td colspan="2" class="ThRows">Update Students</td>
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
<td class="ThRows">Studid</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd0"])) && (!isset($_POST["edit_fd0"]))) {
    $itemvalue = $row[0];
} else {
    $itemvalue = qsrequest("edit_fd0");
}

    $cellvalue = "<input type=\"text\" name=\"edit_fd0\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"15\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Name</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd1"])) && (!isset($_POST["edit_fd1"]))) {
    $itemvalue = $row[1];
} else {
    $itemvalue = qsrequest("edit_fd1");
}

    $cellvalue = "<input type=\"text\" name=\"edit_fd1\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"255\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Course</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd2"])) && (!isset($_POST["edit_fd2"]))) {
    $itemvalue = $row[2];
} else {
    $itemvalue = qsrequest("edit_fd2");
}

    $cellvalue = "<input type=\"text\" name=\"edit_fd2\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"100\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Year</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd3"])) && (!isset($_POST["edit_fd3"]))) {
    $itemvalue = $row[3];
} else {
    $itemvalue = qsrequest("edit_fd3");
}

    $cellvalue = "<select name=\"edit_fd3\" ><option value=\"1st\"" . qscheckselected("1st",$itemvalue,"selected") . ">1st</option><option value=\"2nd\"" . qscheckselected("2nd",$itemvalue,"selected") . ">2nd</option><option value=\"3rd\"" . qscheckselected("3rd",$itemvalue,"selected") . ">3rd</option><option value=\"4th\"" . qscheckselected("4th",$itemvalue,"selected") . ">4th</option></select>";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Sec</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd4"])) && (!isset($_POST["edit_fd4"]))) {
    $itemvalue = $row[4];
} else {
    $itemvalue = qsrequest("edit_fd4");
}

    $cellvalue = "<select name=\"edit_fd4\" ><option value=\"A\"" . qscheckselected("A",$itemvalue,"selected") . ">A</option><option value=\"B\"" . qscheckselected("B",$itemvalue,"selected") . ">B</option><option value=\"C\"" . qscheckselected("C",$itemvalue,"selected") . ">C</option><option value=\"D\"" . qscheckselected("D",$itemvalue,"selected") . ">D</option><option value=\"E\"" . qscheckselected("E",$itemvalue,"selected") . ">E</option><option value=\"F\"" . qscheckselected("F",$itemvalue,"selected") . ">F</option></select>";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Password</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd5"])) && (!isset($_POST["edit_fd5"]))) {
    $itemvalue = $row[5];
} else {
    $itemvalue = qsrequest("edit_fd5");
}

    $cellvalue = "<input type=\"text\" name=\"edit_fd5\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"15\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Sy</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd6"])) && (!isset($_POST["edit_fd6"]))) {
    $itemvalue = $row[6];
} else {
    $itemvalue = qsrequest("edit_fd6");
}

    $cellvalue = "<input type=\"text\" name=\"edit_fd6\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"15\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<?php
#----get back url page----
  $backurl = "./students.php?";
?>
<tr>
<td class="ThRows">&nbsp;</td>
<td class="TrOdd" align=Default>
<input type="hidden" name="act" value="n">
<input type="button" name="QS_Back" value="Back" OnClick="javascript:window.location='<?php print $backurl; ?>'">&nbsp;&nbsp;
<input type="submit" name="QS_Submit" value="Update">&nbsp;&nbsp;
<input type="reset" name="QS_Reset" value="Reset">
</td>
</tr>
</Table><br>


</Form>
<?php
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

