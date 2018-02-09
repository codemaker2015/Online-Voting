
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
$formatdate = array();
$formatdate[0] = "";
$formatdate[1] = "";
$formatdate[2] = "";
$formatdate[3] = "";
$formatdate[4] = "";
$formatdate[6] = "";
$seperatedate = array();
$seperatedate[0] = " ";
$seperatedate[1] = " ";
$seperatedate[2] = " ";
$seperatedate[3] = " ";
$seperatedate[4] = " ";
$seperatedate[6] = " ";
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

$isEditables = array();
$isEditables[0] = 0;
$isEditables[1] = 1;
$isEditables[2] = 1;
$isEditables[3] = 1;
$isEditables[4] = 2;
$isEditables[6] = 1;

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

//Field Related Declarations
$req_Id              = "edit_fd0";
$req_Position        = "edit_fd1";
$req_Name            = "edit_fd2";
$req_Platform        = "edit_fd3";
$req_Picture         = "edit_fd4";
$req_Sy              = "edit_fd6";

//Assign Recordset Field Index
$rs_idx_id           = 0;
$rs_idx_position     = 1;
$rs_idx_name         = 2;
$rs_idx_platform     = 3;
$rs_idx_picture      = 4;
$rs_idx_votecount    = 5;
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
if($_FILES['upload_fd4']['name'] != "") {
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
  if ($currentrow_sql != "") {
    $sql .= " where ".$currentrow_sql ;
  }
    $result =  mysql_query($sql) or die("Invalid query");
    $row = mysql_fetch_array($result);
$uploadDir = "pictures/";
$optUpdate = 1;
$maxFileSize = 1024000;
$fieldFileName = "" . $row[2] . "";
if($_FILES['upload_fd4']['name'] <> "") {
if($_FILES['upload_fd4']['size'] <= $maxFileSize) {
    $ext = substr( $_FILES['upload_fd4']['name'], strrpos( $_FILES['upload_fd4']['name'], "." )+1 );
    $ext1 = substr( $fieldFileName, strrpos( $fieldFileName, "." )+1 );
    if ($ext1 == "") {
        $newFileName = $fieldFileName.".".strtolower($ext);
    }
    elseif ($ext1 == $ext) {
        $newFileName = $fieldFileName;
    }
    else {
        $newFileName = $fieldFileName.".".strtolower($ext); // change the extention to lower case
    }
    $uploadFile = $uploadDir.$newFileName;
	 if (move_uploaded_file($_FILES['upload_fd4']['tmp_name'], $uploadFile)) // Success Upload
    {
        $meta = mysql_fetch_field($result,4);
		     $field_name  = $meta->name;
        if ($value_sql == "") {
		        if ($optUpdate == 0) { // Update with full path
			          $value_sql .= $quotechar.$field_name.$quotechar." = '".$uploadFile."'"  ;
		        } elseif ($optUpdate == 1) { // Update with file name only
			          $value_sql .= $quotechar.$field_name.$quotechar." = '".$newFileName."'"  ;
		        } else {
			          #$value_sql .= $quotechar.$field_name.$quotechar." = '".$row[4]."'"  ;
	          }
	       }
        else {
		        if ($optUpdate == 0) { // Update with full path
			          $value_sql .= ", ".$quotechar.$field_name.$quotechar." = '".$uploadFile."'"  ;
		        } elseif ($optUpdate == 1) { // Update with file name only
			          $value_sql .= ", ".$quotechar.$field_name.$quotechar." = '".$newFileName."'"  ;
		        } else {
			          #$value_sql .= ", ".$quotechar.$field_name.$quotechar." = '".$row[4]."'"  ;
	          }
	       }
    }
	   else // Fail in upload
	   {
		     $err_string = 	"Cannot upload file! There is problem occured when upload."	;
	   }
    } else {
        $err_string = "<font color= red>Your file size is bigger than the maximum size(".$maxFileSize." byte) that we allow to upload</font>";
    }
}
}

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
    $submiturl = "./candidate.php?";
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
    $sql .= "     candidate.`id`,\n";
    $sql .= "     candidate.`position`,\n";
    $sql .= "     candidate.`name`,\n";
    $sql .= "     candidate.`platform`,\n";
    $sql .= "     candidate.`picture`,\n";
    $sql .= "     candidate.`votecount`,\n";
    $sql .= "     candidate.`sy`\n";
    $sql .= " From\n";
    $sql .= "     candidate   candidate\n";
  if ($currentrow_sql != "") {
    $sql .= " where ".$currentrow_sql ;
  }
    $result =  mysql_query($sql) or die("Invalid query");
    $row = mysql_fetch_array($result);
}
?>
<HTML>

<HEAD>


<Title>Update Candidate</Title>


<link rel="stylesheet" type="text/css" href="candidate_edit.css">


   
<script type="text/javascript" src="./js/yahoo-min.js" ></script>
<script type="text/javascript" src="./js/dom-min.js" ></script>
<script type="text/javascript" src="./js/event-min.js" ></script>

<script type="text/javascript">

  // Invoke dbQwikSite Page OnLoad controller as soon as the page is ready 
  // This should always happen first, any user custom-code should run after
  YAHOO.util.Event.onDOMReady( function() { qsPageOnLoadController(); } );  

</script>



	<link rel="stylesheet" type="text/css" href="./css/ContentLayout.css"></link>


<!-- END OF STD-Loader.txt -->


<script type="text/javascript">

// Declares all constants and arrays
// for all page items used on the page

// Declare Field Indexes for all page items
var qsPageItemsCount = 6
var _Id                                       = 0;
var _Position                                 = 1;
var _Name                                     = 2;
var _Platform                                 = 3;
var _Picture                                  = 4;
var _Sy                                       = 5;

// Declare Fields Prompts
var fieldPrompts = [];
fieldPrompts[_Id] = "Id";
fieldPrompts[_Position] = "Position";
fieldPrompts[_Name] = "Name";
fieldPrompts[_Platform] = "Platform";
fieldPrompts[_Picture] = "Picture";
fieldPrompts[_Sy] = "Sy";

// Declare Fields Technical Names
var fieldTechNames = [];
fieldTechNames[_Id] = "Id";
fieldTechNames[_Position] = "Position";
fieldTechNames[_Name] = "Name";
fieldTechNames[_Platform] = "Platform";
fieldTechNames[_Picture] = "Picture";
fieldTechNames[_Sy] = "Sy";

// This function dynamically assigns element 'ID' attributes to all relevant elements
function qsAssignElementIDs() {

  // STEP 1: Assign an ID to all field PROMPTS (TD captions)
  // Scan all table TD tags for those that match field prompts
  var TDs = document.getElementsByTagName("td");
  for (var i=0; i < TDs.length; i++) {
    var element = TDs[i];
    // Check if the TD found is one of the Page Items header
    // This can only be an approximation as some TDs other than the actual field prompts
    // may contain the same caption. In that case all TDs found will carry the same ID.
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
  document.getElementsByName("edit_fd1")[0].id = fieldTechNames[_Position];
  document.getElementsByName("edit_fd2")[0].id = fieldTechNames[_Name];
  document.getElementsByName("edit_fd3")[0].id = fieldTechNames[_Platform];
  document.getElementsByName("upload_fd4")[0].id = fieldTechNames[_Picture];
  document.getElementsByName("edit_fd6")[0].id = fieldTechNames[_Sy];
}


function qsPageItemsAbstraction() {
  qs_form                                  = document.getElementsByName("qs_edit_form")[0];   //Define Form Object by Name.

  pgitm_Position                           = document.getElementsByName("edit_fd1")[0];
  pgitm_Name                               = document.getElementsByName("edit_fd2")[0];
  pgitm_Platform                           = document.getElementsByName("edit_fd3")[0];
  pgitm_Picture                            = document.getElementsByName("upload_fd4")[0];
  pgitm_Sy                                 = document.getElementsByName("edit_fd6")[0];
}

</script>



<script type="text/javascript">



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
  pgitm_Position.focus();
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
    if (!RequiredField(frm.edit_fd1.value)) {
        nIndex++;
        szAlert += "- " +"'Position' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd2.value)) {
        nIndex++;
        szAlert += "- " +"'Name' cannot be blank\n";
    }
    if (!RequiredField(frm.edit_fd3.value)) {
        nIndex++;
        szAlert += "- " +"'Platform' cannot be blank\n";
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
Update Candidate
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


<Form name="qs_edit_form" method="post" action="./candidate_edit.php"  enctype="multipart/form-data" onSubmit="return qsFormOnSubmitController(this)">

<?php
print $hidden_tag;
print $hiddenrow_tag;
?>

<Table Border="0" Cellpadding="2" Cellspacing="1" BgColor="#D4D4D4">

<?php
$css_class = "\"TrOdd\"";
?>
<tr>
<td colspan="2" class="ThRows">Update Candidate</td>
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
$cellvalue = "";
if ((!isset($_GET["edit_fd0"])) && (!isset($_POST["edit_fd0"]))) {
    $itemvalue = $row[0];
} else {
    $itemvalue = qsrequest("edit_fd0");
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
$cellvalue = "";
if ((!isset($_GET["edit_fd1"])) && (!isset($_POST["edit_fd1"]))) {
    $itemvalue = $row[1];
} else {
    $itemvalue = qsrequest("edit_fd1");
}

    $cellvalue = "<select name=\"edit_fd1\" >" . qsmysqlgen_listbox("  Select Distinct `position`,`position` From `position` ","edit_fd1","position","position",$itemvalue) . "</select>";
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
if ((!isset($_GET["edit_fd2"])) && (!isset($_POST["edit_fd2"]))) {
    $itemvalue = $row[2];
} else {
    $itemvalue = qsrequest("edit_fd2");
}

    $cellvalue = "<input type=\"text\" name=\"edit_fd2\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"50\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Platform</td>
<?php
$cellvalue = "";
if ((!isset($_GET["edit_fd3"])) && (!isset($_POST["edit_fd3"]))) {
    $itemvalue = $row[3];
} else {
    $itemvalue = qsrequest("edit_fd3");
}

    $cellvalue = "<input type=\"text\" name=\"edit_fd3\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"255\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Picture</td>
<?php
$cellvalue = "";
if ((!isset($_GET["upload_fd4"])) && (!isset($_POST["upload_fd4"]))) {
    $itemvalue = $row[4];
} else {
    $itemvalue = qsrequest("upload_fd4");
}

    $cellvalue = "<input type=\"file\" name=\"upload_fd4\">";
$cellvalue .= "<br>( ".qsreplace_html_quote(stripslashes($itemvalue))." )";;
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
  $backurl = "./candidate.php?";
?>
<tr>
<td class="ThRows">&nbsp;</td>
<td class="TrOdd" align=Default>
<input type="hidden" name="act" value="n">
<input type="button" name="QS_Back" value="Back" OnClick="javascript:window.location='<?php print $backurl; ?>'">&nbsp;&nbsp;
<input type="submit" name="QS_Submit" value="Update">&nbsp;&nbsp;
<input type="reset"  value="Reset">
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

