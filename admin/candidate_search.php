<?php
require('qs_connection.php');
require('qs_functions.php');
@session_start();
$row = "";
$err_string = "";
$strkeyword = "";
$sortstring = "";
$icon = "";
$sql = "";
$sql_ext = "";
$fields = array();
$fields[0] = "id";
$fields[1] = "position";
$fields[2] = "name";
$fields[3] = "platform";
$fields[4] = "picture";
$fields[5] = "votecount";
$fields[6] = "sy";
$arryitemvalue = array();
$arryitemvalue[0] = "";
$arryitemvalue[1] = "";
$arryitemvalue[2] = "";
$arryitemvalue[3] = "";
$arryitemvalue[4] = "";
$arryitemvalue[5] = "";
$arryitemvalue[6] = "";
$arryopt = array();
$arryopt[0] = "";
$arryopt[1] = "";
$arryopt[2] = "";
$arryopt[3] = "";
$arryopt[4] = "";
$arryopt[5] = "";
$arryopt[6] = "";
$arryandoropt = array();
$arryandoropt[0] = "";
$arryandoropt[1] = "";
$arryandoropt[2] = "";
$arryandoropt[3] = "";
$arryandoropt[4] = "";
$arryandoropt[5] = "";
$arryandoropt[6] = "";
//Field Related Declarations
$req_Position        = "search_fd1";
$req_Name            = "search_fd2";
$req_Sy              = "search_fd6";

//Assign Recordset Field Index
$rs_idx_id           = 0;
$rs_idx_position     = 1;
$rs_idx_name         = 2;
$rs_idx_platform     = 3;
$rs_idx_picture      = 4;
$rs_idx_votecount    = 5;
$rs_idx_sy           = 6;

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
$result = mysql_query($sqltemp . " " . $sql_ext . " limit 0,1")
          or die("Invalid query");
if (!$result){
}
if (isset($_POST["act"])) {
    $_SESSION["candidate_candidate"] = "";
    $_SESSION["candidate_candidate_PageNumber"] = "";
    $filter_string = "";
    $qry_string = "";
    $i = 0;
    while ($i < mysql_num_fields($result)) {
        $meta = mysql_fetch_field($result);
        $field_name = $meta->name;
        $field_type = $meta->type;
        //get field type
        $type_field = "";
        $type_field = returntype($field_type);
        //clear session
        $_SESSION["candidate_search_fd" . $i] = "";
        $_SESSION["candidate_multisearch_fd" . $i] = "";
        $_SESSION["candidate_search_fd_" . $i] = "";
        if ((qsrequest("search_fd" . $i) != "") && (qsrequest("search_fd" . $i) != "*")) {
            $idata = qsrequest("search_fd" . $i);
            if (strlen($idata) > 1) {
                if ($idata[strlen($idata) - 1] == "*") {
                    $idata = substr($idata, 0, strlen($idata) - 1);
                }
            }
            $idata = str_replace("*", "%", $idata);
            $irealdata = $idata;
            if (qsrequest("search_optfd".$i) != "") {
              $idata = qsrequest("search_optfd". $i) . $idata ;
            }
            $iopt = substr($idata, 0, 2);
            if (($iopt == "<=") || ($iopt == "=<")) {
                $iopt = "<=";
                $irealdata = substr($idata, 2);
            } elseif (($iopt == ">=") || ($iopt == "=>")) {
                $iopt = ">=";
                $irealdata = substr($idata, 2);
            } elseif ($iopt == "==") {
                $iopt = "=";
                $irealdata = substr($idata, 2);
            } elseif ($iopt == "<>") {
                $irealdata = substr($idata, 2);
            } elseif ($iopt == "^^") {
                      $iopt = "*";
            	$idata =  $iopt . $irealdata . $iopt; // Contain
                  } elseif ($iopt == "^*") {
                      $iopt = "*";
            	$idata =  $irealdata . $iopt; // Start With
                  } elseif ($iopt == "*^") {
                      $iopt = "*";
            	$idata =  $iopt . $irealdata ; // End With
            } else {
                $iopt = substr($idata, 0, 1);
                if (($iopt == "<") || ($iopt == ">") || ($iopt == "=")) {
                    $irealdata = substr($idata,1);
                } else {
                    $iopt = "=";
                }
            }
            if (qsrequest("andor_optfd".$i) != "") {
                $idata = qsrequest("andor_optfd". $i) . $idata ;
            }
        if (!strcasecmp($idata,"{current date and time}")) {
            $idata = time();
        } elseif (!strcasecmp($idata,"{current date}")) {
            $idata = time();
        } elseif (!strcasecmp($idata,"{current time}")) {
            $idata = time();
        }
            if ($meta) {
                if ($type_field == "type_datetime") {
                    if ((($timestamp = strtotime($irealdata)) !== -1)) {
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . urlencode($idata);
                            $filter_string = $field_name . " like '%" . $idata . "%'";
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . urlencode($idata);
                            $filter_string .= " and " . $field_name . " like '%" . $idata . "%'";
                        }
                        if (qsrequest("search_fd" . $i . "_DateFormat") != ""){
                           $iDateFormat = qsrequest("search_fd" . $i . "_DateFormat"); 
                           $qry_string .= "&search_fd" . $i . "_DateFormat=" . $iDateFormat; 
                        } 
                    } else {
                        $err_string .= "<strong>Error:</strong>while searching.<strong>" . $field_name . "</strong>.<br>";
                        $err_string .= "Description: Invalid DateTime.<br>";
                    }
                } elseif ($type_field == "type_integer") {
                    if (is_numeric($irealdata)) {
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . $idata;
                            $filter_string = $field_name . " " . $iopt . " " . $irealdata;
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . $idata;
                            $filter_string .= " and " . $field_name . " " . $iopt . " " . $irealdata;
                        }
                    } else {
                        $err_string .= "<strong>Error:</strong>while searching.<strong>" . $field_name . "</strong>.<br>";
                        $err_string .= "Description: Type mismatch.<br>";
                    }
                } elseif ($type_field == "type_string") {
                    if ($qry_string == "") {
                        $qry_string = "search_fd" . $i . "=" . urlencode(stripslashes($idata));
                        $filter_string = $field_name . " like '" . $irealdata . "%'";
                    } else {
                        $qry_string .= "&search_fd" . $i . "=" . urlencode(stripslashes($idata));
                        $filter_string .= " and " . $field_name . " like '" . $irealdata . "%'";
                    }
                } else {
                    if ($qry_string == "") {
                        $qry_string = "search_fd" . $i . "=" . urlencode(stripslashes($idata));
                        $filter_string = $field_name . " = '" . $irealdata . "'";
                    } else {
                        $qry_string .= "&search_fd" . $i . "=" . urlencode(stripslashes($idata));
                        $filter_string .= " and " . $field_name . " = '" . $irealdata . "'";
                    }
                }
            }
        }
        if (qsrequest("multisearch_fd" . $i) != "") {
            if ($qry_string == "") {
                $qry_string = "multisearch_fd" . $i . "=" . qsrequest("multisearch_fd" . $i);
            } else {
                $qry_string = $qry_string . "&multisearch_fd" . $i . "=" . qsrequest("multisearch_fd" . $i);
            }
        }
        //begin search between see variable 'search_fd_(n)'
        if (qsrequest("search_fd_" . $i) != "") {
            $idata = qsrequest("search_fd_" . $i);
            if (strlen($idata) > 1) {
                if ($idata[strlen($idata) - 1] == "*") {
                    $idata = substr($idata, 0, strlen($idata) - 1);
                }
            }
            $idata = str_replace("*", "%", $idata);
            $irealdata = $idata;
            if (qsrequest("search_optfd_".$i) != "") {
                $idata = qsrequest("search_optfd_". $i) . $idata ;
            }
            $iopt = qsrequest("search_optfd_". $i);
            if ($meta) {
                if ($type_field == "type_datetime") {
                   if ((($timestamp = strtotime($irealdata)) !== -1)) {
                       if ($qry_string == "") {
                           $qry_string = "search_fd_" . $i . "=" . urlencode($idata);
                           $filter_string = $field_name . " like '%" . $idata . "%'";
                       } else {
                           $qry_string .= "&search_fd_" . $i . "=" . urlencode($idata);
                           $filter_string .= " and " . $field_name . " like '%" . $idata . "%'";
                       }
                        if (qsrequest("search_fd_" . $i . "_DateFormat") != ""){
                           $iDateFormat = qsrequest("search_fd_" . $i . "_DateFormat"); 
                           $qry_string .= "&search_fd_" . $i . "_DateFormat=" . $iDateFormat; 
                        } 
                   } else {
                       $err_string .= "<strong>Error:</strong>while searching.<strong>" . $field_name . "</strong>.<br>";
                       $err_string .= "Description: Invalid DateTime.<br>";
                   }
                } elseif ($type_field == "type_integer") {
                   if (is_numeric($irealdata)) {
                       if ($qry_string == "") {
                           $qry_string = "search_fd_" . $i . "=" . $idata;
                           $filter_string = $field_name . " " . $iopt . " " . $irealdata;
                       } else {
                           $qry_string .= "&search_fd_" . $i . "=" . $idata;
                           $filter_string .= " and " . $field_name . " " . $iopt . " " . $irealdata;
                       }
                   } else {
                        $err_string .= "<strong>Error:</strong>while searching.<strong>" . $field_name . "</strong>.<br>";
                        $err_string .= "Description: Type mismatch.<br>";
                   }
               }
           }
        }
        $i++;
    }
    if ($result > 0) {mysql_free_result($result);}
  if (qsrequest("search_sort") <> "") {
	    $sortstring = qsrequest("search_sort");
  }
  if (qsrequest("page_size") <> "") {
	    $page_size = qsrequest("page_size");
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
} else {
    $sortstring  = "";
    if (qssession("sortfield") != "") {
         $sortstring = "&sortfield=" . urlencode(stripslashes(qssession("sortfield"))) . "&sortby=" . urlencode(stripslashes(qssession("sortby")));
    }
    if (qssession("page_size") != "") {
         $page_size = urlencode(stripslashes(qssession("page_size")));
    }
    $i=0;
    while ($i < mysql_num_fields($result)) {
$strkeyword = "";
$iopt = "";
$idata = "";
if ((!isset($_GET["search_fd".$i])) && (!isset($_POST["search_fd".$i]))) {
    $arryitemvalue[$i] = "";
    $arryopt[$i]="";
} else {
    # Check value for advance search
    $idata = qsrequest("search_fd" . $i);
    $icon = "";
    if (substr($idata, 0, 2) == "||") {
        $icon = "||";
        $idata = substr($idata, 2);
    }
    $iopt = substr($idata, 0, 2); // Get 2 of left keyword
    if (($iopt == "<=") || ($iopt == "=<")){
        $iopt  = "<=";
        $strkeyword = substr($idata, 2);
    }elseif (($iopt == ">=") || ($iopt == "=>")){
        $iopt = ">=";
        $strkeyword = substr($idata, 2);
    }elseif ($iopt == "==" ){
        $iopt = "==";
        $strkeyword = substr($idata, 2);
    }elseif ($iopt == "<>"){
        $strkeyword = substr($idata, 2);
    } else {
        $startstrdata = substr($idata,0,1) ;
        $endstrdata = $idata[strlen($idata) - 1];
        if (($startstrdata != "%" ) && ($endstrdata != "%")){
            if (($startstrdata == "<") || ($startstrdata == ">") || ($startstrdata == "=")) {
                if ($startstrdata == "<") {
                    $strkeyword = str_replace("<","", $idata) ;
                    $iopt = "<";
                }elseif($startstrdata == ">")  {
                    $strkeyword = str_replace(">","", $idata) ;
                    $iopt = ">";
                } else {
                    $strkeyword = str_replace("=","", $idata) ;
                    $iopt = "=";
                }
            }else {
                $arryitemvalue[$i] = $idata;
                $strkeyword = $idata;
            }
        }else {
            if (($startstrdata == "%" ) && ($endstrdata == "%")) {  # Contain Case
                $startstrdata = str_replace("%","", $idata) ;
                $strkeyword  = substr($idata,1, (strlen($idata)-2));
                $iopt = "^^" ;
            }elseif (($startstrdata != "%" ) && ($endstrdata == "%")) {  # Start With Case xx*
                $strkeyword  = substr($idata,0, (strlen($idata)-1));
                $iopt = "^*";
            }elseif (($startstrdata == "%" ) && ($endstrdata != "%")) { # End With Case *xx
                $strkeyword  = substr($idata,1, (strlen($idata)));
                $iopt = "*^";
            }
        }// end eheck one charator
   }//end of check 2 first character
   $arryitemvalue[$i] = $strkeyword;
   $arryopt[$i] = $iopt;
   $arryandoropt[$i] = $icon;
}
$i += 1;
}// end while
if ($result > 0) {mysql_free_result($result);}
}
?>
<HTML>

<HEAD>

<Title>Find Candidate</Title>

<link rel="stylesheet" type="text/css" href="candidate_search.css">
  
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
var qsPageItemsCount = 3
var _Position                                 = 0;
var _Name                                     = 1;
var _Sy                                       = 2;

// Declare Fields Prompts
var fieldPrompts = [];
fieldPrompts[_Position] = "Position";
fieldPrompts[_Name] = "Name";
fieldPrompts[_Sy] = "Sy";

// Declare Fields Technical Names
var fieldTechNames = [];
fieldTechNames[_Position] = "Position";
fieldTechNames[_Name] = "Name";
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
  document.getElementsByName("search_fd1")[0].id = fieldTechNames[_Position];
  document.getElementsByName("search_fd2")[0].id = fieldTechNames[_Name];
  document.getElementsByName("search_fd6")[0].id = fieldTechNames[_Sy];
}

 
function qsPageItemsAbstraction() {
  qs_form                                  = document.getElementsByName("qs_search_form")[0];   //Define Form Object by Name.
  pgitm_Position                           = document.getElementsByName("search_fd1")[0];
  pgitm_Name                               = document.getElementsByName("search_fd2")[0];
  pgitm_Sy                                 = document.getElementsByName("search_fd6")[0];
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
  pgitm_Position.focus();
   return true;                                        
}                                                      

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


<script language='javascript' src='qwikcalendar.js'></script>

<meta name="generator" content="dbQwikSite Ecommerce"><meta name="dbQwikSitePE" content="QSFREEPE">

</HEAD>

<BODY>


<Center>
<center><hr /><font size="5">
Find Candidate
</font><hr /></center><br>



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


<A NAME=top></A>
<Form name="qs_search_form" method="post" action="./candidate_search.php" onSubmit="return qsFormOnSubmitController(this)"  onReset="return qsPageOnResetController(this)" >


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


<Table Border="0" Cellpadding="2" Cellspacing="1" BgColor="#D4D4D4">

<tr>
<td colspan="2" class="ThRows">Find Candidate</td>
</tr>
<?php
$css_class = "\"TrOdd\"";
if ($err_string != "") {
    print "<tr>";
    print "<td class=\"ThRows\"><Strong>Error:</Strong></td>";
    print "<td class=" . $css_class . " align=Default>" . $err_string . "</td>";
    print "</tr>";
}
?>
<tr>
<td class="ThRows">Position</td>
<?php
$cellvalue = "";
if ((!isset($_GET["search_fd1"])) && (!isset($_POST["search_fd1"]))) {
    $itemvalue = "";
} else {
    $itemvalue = $arryitemvalue[1];
}

    $cellvalue = "<input type=\"text\" name=\"search_fd1\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"50\" >" . "<input type=\"hidden\" name=\"multisearch_fd1\" value=\"\">";
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
if ((!isset($_GET["search_fd2"])) && (!isset($_POST["search_fd2"]))) {
    $itemvalue = "";
} else {
    $itemvalue = $arryitemvalue[2];
}

    $cellvalue = "<input type=\"text\" name=\"search_fd2\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"50\" >" . "<input type=\"hidden\" name=\"multisearch_fd2\" value=\"\">";
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
if ((!isset($_GET["search_fd6"])) && (!isset($_POST["search_fd6"]))) {
    $itemvalue = "";
} else {
    $itemvalue = $arryitemvalue[6];
}

    $cellvalue = "<input type=\"text\" name=\"search_fd6\" value=\"" . qsreplace_html_quote(stripslashes($itemvalue)) . "\" size=\"30\"  maxlength=\"15\" >" . "<input type=\"hidden\" name=\"multisearch_fd6\" value=\"\">";
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
<td colspan="2" class="ThRows" align=Center>
<input type="hidden" name="act" value="n">
<input type="button" name="QS_Back" value="Back" OnClick="javascript:window.location='<?php print $backurl; ?>'">&nbsp;&nbsp;
<input type="submit" name="QS_Submit" value="Search">&nbsp;&nbsp;
<input type="button" name="QS_Clear" value="Clear" OnClick="location='candidate_search.php'">
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
