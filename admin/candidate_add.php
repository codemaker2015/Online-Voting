
<?php

require('qs_connection.php');
require('qs_functions.php');

@session_start();

$row = "";
$err_string = "";
$updateCond = "";
$RDBMS_Type= "MySQL"; 
if (isset($_GET["page"])) {
    $current_page = $_GET["page"];
} elseif (isset($_POST["page"])) {
    $current_page = $_POST["page"];
} else {
    $current_page = 1;
}
$quotechar = "`";
$quotedate = "'";
$hidden_tag = "";
$result = "";
$sql = "";
$sql_ext = "";
$formatdate = array();
$formatdate[1] = "";
$formatdate[2] = "";
$formatdate[3] = "";
$formatdate[4] = "";
$formatdate[6] = "";
$seperatedate = array();
$seperatedate[1] = " ";
$seperatedate[2] = " ";
$seperatedate[3] = " ";
$seperatedate[4] = " ";
$seperatedate[6] = " ";
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
$req_Position        = "add_fd1";
$req_Name            = "add_fd2";
$req_Platform        = "add_fd3";
$req_Picture         = "add_fd4";
$req_Sy              = "add_fd6";

//Assign Recordset Field Index
$rs_idx_id           = 0;
$rs_idx_position     = 1;
$rs_idx_name         = 2;
$rs_idx_platform     = 3;
$rs_idx_picture      = 4;
$rs_idx_votecount    = 5;
$rs_idx_sy           = 6;

if (isset($_POST["act"])) {
   $ProcessForm  = "Y"; 
   if ($ProcessForm  == "Y") { 
if (strpos(strtoupper($sql), " WHERE ")) {
   $sqltemp = $sql . " AND (1=0) ";
}else{
   $sqltemp = $sql . " Where (1=0) ";
}

$result = mysql_query($sqltemp . " " . $sql_ext . " limit 0,1")
          or die("Invalid query");
$qry_string = "";
$insert_sql = "";
$value_sql = "";
$i = 0;
$SourceFileUpload = array();
$DestFileUpload = array();
$NewFieldUpload = array();
 
//Set initial value for array
$SourceFileUpload[0] = "";
$DestFileUpload[0] = "";
$NewFieldUpload[0] = "";
$SourceFileUpload[1] = "";
$DestFileUpload[1] = "";
$NewFieldUpload[1] = "";
$SourceFileUpload[2] = "";
$DestFileUpload[2] = "";
$NewFieldUpload[2] = "";
$SourceFileUpload[3] = "";
$DestFileUpload[3] = "";
$NewFieldUpload[3] = "";
$SourceFileUpload[4] = "";
$DestFileUpload[4] = "";
$NewFieldUpload[4] = "";
 
while ($i < mysql_num_fields($result)) {
    $meta = mysql_fetch_field($result);
    $field_name = $meta->name;
    $field_type = $meta->type;
    $type_field = "";
    $type_field = returntype($field_type);
    if (qsvalidRequest("search_fd" .$i)) {
        if ($qry_string == "") {
            $qry_string = "search_fd" . $i . "=" . urlencode(stripslashes(qsrequest("search_fd" . $i)));
        } else {
            $qry_string .= "&search_fd" .$i . "=" . urlencode(stripslashes(qsrequest("search_fd" . $i)));
        }
        $hidden_tag .= "<input type=\"hidden\" name=\"search_fd" .$i . "\" value=\"" . qsreplace_html_quote(stripslashes(qsrequest("search_fd" . $i))) . "\">\n";
        if ($qry_string == "") {
            $qry_string = "multisearch_fd" . $i . "=" . urlencode(stripslashes(qsrequest("multisearch_fd" . $i)));
        } else {
            $qry_string .= "&multisearch_fd" .$i . "=" . urlencode(stripslashes(qsrequest("multisearch_fd" . $i)));
        }
        $hidden_tag .= "<input type=\"hidden\" name=\"multisearch_fd" .$i . "\" value=\"" . qsreplace_html_quote(stripslashes(qsrequest("multisearch_fd" . $i))) . "\">\n";
    }
    if (qsvalidRequest("add_fd" . $i)) {
        $idata = qsrequest("add_fd" . $i);
        if ($meta) {
            if ($type_field == "type_datetime") {


                    if ($insert_sql == "") {
                        $insert_sql .= $quotechar . $field_name . $quotechar;
                        $value_sql  .= $quotedate . qsconvertdate2ansi($idata,$formatdate[$i],$seperatedate[$i]) .  $quotedate;
                    } else {
                        $insert_sql .= "," . $quotechar . $field_name . $quotechar;
                        $value_sql  .= "," . $quotedate . qsconvertdate2ansi($idata,$formatdate[$i],$seperatedate[$i]) .$quotedate;
                    }
            } elseif ($type_field == "type_integer") {
                $idata = QSConvert2EngNumber($idata); 

                if (is_numeric($idata)) {
                    if ($insert_sql == "") {
                        $insert_sql .= $quotechar . $field_name . $quotechar;
                        $value_sql  .= $idata;
                    } else {
                        $insert_sql .= "," . $quotechar . $field_name . $quotechar;
                        $value_sql  .= "," . $idata;
                    }
                } else {
                    $err_string .= "<strong>Error:</strong>while adding<strong>" . $field_name . "</strong>.<br>";
                    $err_string .= "Description: Type mismatch.<br>";
                }
            } elseif ($type_field == "type_string") {


                if ($insert_sql == "") {
                    $insert_sql .= $quotechar . $field_name . $quotechar;
                    $value_sql  .= "'" . ereg_replace("'","''",stripslashes($idata)) . "'";
                } else {
                    $insert_sql .= "," . $quotechar . $field_name . $quotechar;
                    $value_sql  .= ",'" . ereg_replace("'","''",stripslashes($idata)) . "'";
                }
            } else {


                if ($insert_sql == "") {
                    $insert_sql .= $quotechar . $field_name . $quotechar;
                    $value_sql  .= "'" . ereg_replace("'","''",stripslashes($idata)) . "'";
                } else {
                    $insert_sql .= "," . $quotechar . $field_name . $quotechar;
                    $value_sql  .= ",'" . ereg_replace("'","''",stripslashes($idata)) . "'";
                }
            }

        }
    } else {
        if ((strtolower($field_type) != "int identity")
         && (strtolower($field_type) != "autoincrement")
         && (strtolower($field_type) != "counter")) {
            if ($insert_sql == "") {
                $insert_sql .= $quotechar . $field_name . $quotechar;
                $value_sql  .= "null";
            } else {
                $insert_sql .= "," . $quotechar . $field_name . $quotechar;
                $value_sql  .= ", null";
            }
        }
    }
$i++;
}
if(isset($_POST['QS_Submit']))
{
$sql  = "";
$sql  = "insert into " . $quotechar. mysql_field_table($result,0) . $quotechar;
$sql .= " (" . $insert_sql . ")";
$sql .= " values";
$sql .= " (" . $value_sql . ")";

    $submiturl = "./candidate.php?";
    if ($result > 0) {mysql_free_result($result);}
    if (!$result = @mysql_query($sql)){
        $err_string .= "<strong>Error:</strong>while adding<br>" . mysql_error();
  } else { 
   }
    
    //Start update data of upload field
        $value_sql = "";
        $max_id = -1;
        $max_id = GetLastRecordID($RDBMS_Type, "Direct", "candidate", "id", $conn );
        $updateCond = "";
        $updateCond = "id=". $max_id;
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
  if ($updateCond != "") {
    $sql .= " where " . $updateCond;
  }
    $result =  mysql_query($sql) or die("Invalid query");
    $row = mysql_fetch_array($result);
$uploadDir = "pictures/";
$optUpdate = 1;
$maxFileSize = 2000000;
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

if (($err_string=="")&&($value_sql!="")) {
    $sql  = "";
    $sql  = "update " . $quotechar . mysql_field_table($result,0) . $quotechar;
    $sql .= " set " . $value_sql;
    $sql .= " where ";
    $sql .= $updateCond;
    if ($result > 0) {mysql_free_result($result);}
    if (!$result = @mysql_query($sql)){
        $err_string .= "<strong>Error:</strong>while updating<br>" . mysql_error();
    }
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
  } //end if ProcessForm
}
} //end if act
?>
<HTML>
<?php

?>
<HEAD>

<Title>Add Candidate</Title>

<link rel="stylesheet" type="text/css" href="candidate_add.css">
  
<script type="text/javascript" src="./js/yahoo-min.js" ></script>
<script type="text/javascript" src="./js/dom-min.js" ></script>
<script type="text/javascript" src="./js/event-min.js" ></script>

<script type="text/javascript">

  
  YAHOO.util.Event.onDOMReady( function() { qsPageOnLoadController(); } );  

</script>


	<link rel="stylesheet" type="text/css" href="./css/ContentLayout.css"></link>


<!-- END OF STD-Loader.txt -->


<script type="text/javascript">

// Declares all constants and arrays
// for all page items used on the page

// Declare Field Indexes for all page items
var qsPageItemsCount = 5
var _Position                                 = 0;
var _Name                                     = 1;
var _Platform                                 = 2;
var _Picture                                  = 3;
var _Sy                                       = 4;

// Declare Fields Prompts
var fieldPrompts = [];
fieldPrompts[_Position] = "Position";
fieldPrompts[_Name] = "Name";
fieldPrompts[_Platform] = "Platform";
fieldPrompts[_Picture] = "Picture";
fieldPrompts[_Sy] = "Sy";

// Declare Fields Technical Names
var fieldTechNames = [];
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
  document.getElementsByName("add_fd1")[0].id = fieldTechNames[_Position];
  document.getElementsByName("add_fd2")[0].id = fieldTechNames[_Name];
  document.getElementsByName("add_fd3")[0].id = fieldTechNames[_Platform];
  document.getElementsByName("upload_fd4")[0].id = fieldTechNames[_Picture];
  document.getElementsByName("add_fd6")[0].id = fieldTechNames[_Sy];
}


function qsPageItemsAbstraction() {
  qs_form                                  = document.getElementsByName("qs_add_form")[0];   //Define Form Object by Name.
  pgitm_Position                           = document.getElementsByName("add_fd1")[0];
  pgitm_Name                               = document.getElementsByName("add_fd2")[0];
  pgitm_Platform                           = document.getElementsByName("add_fd3")[0];
  pgitm_Picture                            = document.getElementsByName("upload_fd4")[0];
  pgitm_Sy                                 = document.getElementsByName("add_fd6")[0];
}

</script>



<script type="text/javascript">

// This function dynamically assigns custom events
// to page item controls on this page
function qsAssignPageItemEvents() {
}

</script>




<!-- >> START OF "Add Candidate ->
<style type="text/css">
<!--
.style1 {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
}
-->
</style>
 
<!-- << END OF "Add Candidate -> Client Includes" [clientincludes] [PAGEEVENT] [START] [JS] [07385BCC-B051-4DA5-A76A-6AC2805C875E]-->




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
    if (!RequiredField(frm.add_fd1.value)) {
        nIndex++;
        szAlert += "- " +"'Position' cannot be blank\n";
    }
    if (!RequiredField(frm.add_fd2.value)) {
        nIndex++;
        szAlert += "- " +"'Name' cannot be blank\n";
    }
    if (!RequiredField(frm.add_fd3.value)) {
        nIndex++;
        szAlert += "- " +"'Platform' cannot be blank\n";
    }
		
    if (!RequiredField(frm.add_fd6.value)) {
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

</HEAD>

<BODY>


<Center>
<center><hr />
  <span class="style1"><font size="5">
Add Candidate
  </font></span>
  <hr /></center><br>


<A NAME=top></A>

<table id="QS_Content_Layout_1_Table">
  <tr id="QS_Content_Layout_1_TopRow">
    <td id="QS_Content_Layout_1_NorthWest">
            <div id="QS_Content_Layout_1_NorthWestDiv">

        </div>
    </td>
    <td id="QS_Content_Layout_1_North">
            <div id="QS_Content_Layout_1_NorthDiv">
<?php

?>
        </div>
    </td>
    <td id="QS_Content_Layout_1_NorthEast">
            <div id="QS_Content_Layout_1_NorthEastDiv">
<?php

?>
        </div>
    </td>
  </tr>
  <tr id="QS_Content_Layout_1_MiddleRow">
    <td id="QS_Content_Layout_1_West">
            <div id="QS_Content_Layout_1_WestDiv">
<?php

?>
        </div>
    </td>
    <td id="QS_Content_Layout_1_Center">
            <div id="QS_Content_Layout_1_CenterDiv">
<?php

?>


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


<Form name="qs_add_form" method="post" action="./candidate_add.php"  enctype="multipart/form-data" onSubmit="return qsFormOnSubmitController(this)">
<?php

?>
<?php
print $hidden_tag;
?>
<Table Border="0" Cellpadding="2" Cellspacing="1" BgColor="#D4D4D4">

<?php
$css_class = "\"TrOdd\"";
?>
<tr>
<td colspan="2" class="ThRows">Add Candidate</td>
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
<td class="ThRows">Student No.</td>
<td align="left" class="TrOdd"><input name="idno" type="text" id="idno" style="font-weight:bold" onChange="this.form.submit()" value="<?php echo $_POST['idno'] ?>" size="15" ></td>
</tr>
<tr>
<?php 
if($_POST['idno'])
{

$idno = mysql_query("select * from students where studid = '$_POST[idno]'");
$a=mysql_num_rows($idno);
$b=mysql_fetch_array($idno);

}
if($a<=0) {
$disable="readonly=\"$false\"";
$name= "";
?>
<tr>
<td class="ThRows" colspan="2"><span style="color:#990033""><?php echo $_POST['idno']." Not Found"; ?></span></td>
</tr>
<?php
} else {
$disable="";
$name= $b['name'];
}
?>
<tr>

<td class="ThRows">Position</td>
<?php
$cellvalue = "";
if ((!isset($_GET["add_fd1"])) && (!isset($_POST["add_fd1"]))) {
    $itemvalue = "";
} else {
    $itemvalue = qsrequest("add_fd1");
}

    $cellvalue = "<select $disable name=\"add_fd1\" ><option value=\"\"" . qscheckselected("",$itemvalue,"selected") . ">-- Select --</option>" . qsmysqlgen_listbox("  Select Distinct `position`,`position` From `position` ","add_fd1","position","position",$itemvalue) . "</select>";
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
if ((!isset($_GET["add_fd2"])) && (!isset($_POST["add_fd2"]))) {
    $itemvalue = "";
} else {
	if ($a <= 0) {	
    $itemvalue = "";
	} elseif($a > 0) {
	$itemvalue = $name;
	} else {
	$itemvalue = qsrequest("add_fd2");
	}
}

    $cellvalue = "<input $disable\" type=\"text\" name=\"add_fd2\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"50\" >";
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
if ((!isset($_GET["add_fd3"])) && (!isset($_POST["add_fd3"]))) {
    $itemvalue = "";
} else {
    $itemvalue = qsrequest("add_fd3");
}

    $cellvalue = "<input $disable type=\"text\" name=\"add_fd3\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"255\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>
</tr>
<tr>
<td class="ThRows">Picture</td>
<?php

    $cellvalue = "<input $disable type=\"file\" name=\"upload_fd4\">";
;
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print "<td class=" . $css_class . " align=Default >" . $cellvalue . "</td>";
?>

<?php
$cellvalue = "0";
if ((!isset($_GET["add_fd5"])) && (!isset($_POST["add_fd5"]))) {
    $itemvalue = "0";
} else {
    $itemvalue = "0";
}

    $cellvalue = "<input type=\"hidden\" name=\"add_fd5\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"15\" >";
    if ($cellvalue == "") {
        $cellvalue = "&nbsp;";
    }

    print " " . $cellvalue . "";
?>
</tr>
<tr>
<td class="ThRows">Sy</td>
<?php
$cellvalue = "";
if ((!isset($_GET["add_fd6"])) && (!isset($_POST["add_fd6"]))) {
    $itemvalue = "";
} else {
    $itemvalue = qsrequest("add_fd6");
}

    $cellvalue = "<input $disable type=\"text\" name=\"add_fd6\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"15\" >";
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
<input type="submit" name="QS_Submit" value="Add">&nbsp;&nbsp;
<input type="reset" name="QS_Reset" value="Reset"></td>
</tr>
</Table>
<br>
</Form>
<?php
?>
<?php
if ($result > 0) {mysql_free_result($result);}
if ($link > 0) {mysql_close($link);}
?>
<?php
?>
        </div>
    </td>
    <td id="QS_Content_Layout_1_East">
            <div id="QS_Content_Layout_1_EastDiv">
<?php
?>
        </div>
    </td>
  </tr>
  <tr id="QS_Content_Layout_1_BottomRow">
    <td id="QS_Content_Layout_1_SouthWest">
            <div id="QS_Content_Layout_1_SouthWestDiv">
<?php
?>
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

<div id="Footer" align="center"> 
 <p>&copy; <?php echo date('Y');?>.All rights reserved E-voting system</p>
        <p>Designed & Developed by  <b>Harjeet kaur</b></p>
		
  </div>

</BODY>

</HTML>

