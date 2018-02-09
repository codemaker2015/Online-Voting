<?php
?>
<?php
 
@session_start();
$_SESSION["SkipConnectMySQL"] = "";
require('qs_connection.php');
require('qs_functions.php');
$err_string="";
$quotechar = "`";
$quotedate = "'";
$sql = "";
$sql_ext = "";
$SQL_GroupOnly = "";
$sqlmaster = "";
$sql_extmaster = "";
$cellvalue = "";
$istrdata = "";
$icon = "";
$ioldcon = "";
$sortstring = "";
$parammaster = array();
$fields = array();
$intColCount = 0;
$intColIndex = 0;
$fields[0] = "students.`studid`";
$parammaster[0] = "";
$fields[1] = "students.`name`";
$parammaster[1] = "";
$fields[2] = "students.`course`";
$parammaster[2] = "";
$fields[3] = "students.`year`";
$parammaster[3] = "";
$fields[4] = "students.`sec`";
$parammaster[4] = "";
$fields[5] = "students.`password`";
$parammaster[5] = "";
$fields[6] = "students.`sy`";
$parammaster[6] = "";
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


$searchmode = array();
$stdsearchopt = array();
$searchmode[0] = 0;
$stdsearchopt[0]=1;
$searchmode[1] = 0;
$stdsearchopt[1]=0;
$searchmode[2] = 0;
$stdsearchopt[2]=1;
$searchmode[3] = 0;
$stdsearchopt[3]=1;
$searchmode[4] = 0;
$stdsearchopt[4]=1;
$searchmode[5] = 0;
$stdsearchopt[5]=0;
$searchmode[6] = 0;
$stdsearchopt[6]=1;
//Field Related Declarations
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
if(!$result = @mysql_query($sqltemp . " " . $sql_ext . " limit 0,1")){
    $err_string .= "<strong>Error:</strong>while connecting to database<br>" . mysql_error();
}
if ($err_string != "") {
    print "<Center><Table Border=\"0\" Cellspacing=\"1\" bgcolor=\"#CCCCCC\" >";
    print "<tr>";
    print "<td height=\"80\" align=\"Default\" bgcolor=\"#FFFFFF\">";
    print "<font color=\"#000099\" size=\"2\">";
    print $err_string;
    print "</font>";
    print "</td>";
    print "</tr>";
    print "</Table></Center>";
    exit;
} //==end if $err_string != ""
if (qsrequest("clearsession") == "1") {
 $_SESSION["students_students"] = "";
    $_SESSION["students_students_PageNumber"] = "";
} //==end if clearsession
$filter_string = "";
$filter_stringmaster = "";
$qry_string = "";
$i = 0;
$searchendkey ="";
$searchstartkey = "";
while ($i < mysql_num_fields($result)) {
    $meta = mysql_fetch_field($result);
    $field_name  = $meta->name;
    $field_table = $meta->table;
    $field_type  = $meta->type;
    $type_field = "";
    $type_field = returntype($field_type);
    if (($searchmode[$i])==0) { # 0 = Std, 1 = Advance
        if (($stdsearchopt[$i])==0) { //==0=Contain , 1 = Equal : for standard mode
            $searchstartkey = "%";
            $searchendkey = "%";
        } else {
            $searchstartkey = "";
            $searchendkey = "";
        }
    } else { //==end if searchmode = 0
        $searchstartkey= "";
        $searchendkey = "";
    } //==end if searchmode <> 0
    if (qsrequest("clearsession") == "1") {
        $_SESSION["students_search_fd" . $i] = "";
        $_SESSION["students_multisearch_fd" . $i] = "";
        $_SESSION["students_search_fd_" . $i] = "";
        $_SESSION["students_search_fd" . $i . "_DateFormat"] = "";
        $_SESSION["students_search_fd_" . $i . "_DateFormat"] = "";
    } //==end if clearsession
    if (qsrequest("search_fd" . $i) != "") {
        $_SESSION["students_search_fd" . $i] = qsrequest("search_fd" . $i);
    }
    if (qsrequest("multisearch_fd" . $i) != "") {
        $_SESSION["students_multisearch_fd" . $i] = qsrequest("multisearch_fd" . $i);
    }
    if (qsrequest("search_fd_" . $i) != "") {
        $_SESSION["students_search_fd_" . $i] = qsrequest("search_fd_" . $i);
    }
    //Prepare date format of each item search to Session
    if (qsrequest("search_fd" . $i . "_DateFormat") != "") { 
      $_SESSION["students_search_fd" . $i . "_DateFormat"] = qsrequest("search_fd" . $i . "_DateFormat"); 
    }
    if (qsrequest("search_fd_" . $i . "_DateFormat") != "") { 
      $_SESSION["students_search_fd_" . $i . "_DateFormat"] = qsrequest("search_fd_" . $i . "_DateFormat"); 
    }
    if ((qssession("students_search_fd" . $i) != "") && (qssession("students_search_fd" . $i) != "*")) {
        $idata = qssession("students_search_fd" . $i);
        $icon = " AND ";
        $ioldcon = "";
        if (substr($idata, 0, 2) == "||") {
            $icon = " Or ";
            $ioldcon = "||";
            $iopt = substr($idata, 2, 2);
            $idata = substr($idata, 2);
        }else{
            $iopt = substr($idata, 0, 2);
        }
        $idata = str_replace("*", "%", $idata);
        $irealdata = $idata;
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
        } else {
            $iopt = substr($idata, 0, 1);
            if (($iopt == "<") || ($iopt == ">") || ($iopt == "=")) {
                $irealdata = substr($idata,1);
            } else {
                $iopt = "=";
            }
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
                    if (($iopt)=="="){
                        $conditionstr = " = ";
                    		 $istrdata = str_replace("=", "", $istrdata);
                    } else {
                        $conditionstr = $iopt;
                    		 $istrdata = $irealdata;
                    		 $searchstartkey = "";
                    		 $searchendkey   = "";
                    }
                    //Prepare  date format for each item search then convert to compatible format 
                    if (qssession("students_search_fd" . $i . "_DateFormat") != ""){ 
                      $iDateFormat = qssession("students_search_fd" . $i . "_DateFormat"); 
                    } else { 
                      $iDateFormat = ""; 
                    } 
                    if ((qssession("students_multisearch_fd" . $i) != "")) {
                        $multisearch = qssession("students_multisearch_fd" . $i);
                        $searcharray = split(",",$multisearch);
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                            $filter_string = "(" . $fields[$i] . $conditionstr . " ". $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            $j = 0;
                            for ($j = 0; $j < count($searcharray); $j++) {
                                $searchindex = $searcharray[$j];
                                $filter_string .= " OR " . $fields[$searchindex]  . $conditionstr . " ". $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            }
                            $filter_string .= ")";
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                            $filter_string .= " AND (" . $fields[$i]  . $conditionstr . " " . $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            $j = 0;
                            for ($j = 0; $j < count($searcharray); $j++) {
                                $searchindex = $searcharray[$j];
                                $filter_string .= " OR " . $fields[$searchindex]  . $conditionstr . " " . $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            }
                            $filter_string .= ")";
                        }
                    } else {
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $filter_string = $fields[$i]  . $conditionstr . " " . $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            if ($parammaster[$i] != ""){
                                $filter_stringmaster = $parammaster[$i]  . $conditionstr . " " . $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            }
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $filter_string .= $icon . $fields[$i]  . $conditionstr . " " . $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            if ($parammaster[$i] != ""){
                                $filter_stringmaster .= $icon . $parammaster[$i]  . $conditionstr . " " . $quotedate . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $quotedate;
                            }
                        }
                    }
                } else {
                    $err_string .= "<strong>Error:</strong>while searching.<strong>" . $field_name . "</strong>.<br>";
                    $err_string .= "Description: Invalid DateTime.<br>";
                }
            //==end $type_field == "type_datetime"
            } elseif ($type_field == "type_integer") {
                $irealdata = str_replace("%", "", $irealdata);
                if (is_numeric($irealdata)) {
                    if ((qssession("students_multisearch_fd" . $i) != "")) {
                        $multisearch = qssession("students_multisearch_fd" . $i);
                        $searcharray = split(",",$multisearch);
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . $ioldcon . $idata;
                            $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                            $filter_string = "(" . $fields[$i] . " " . $iopt . " " . $irealdata;
                            $j = 0;
                            for ($j = 0; $j < count($searcharray); $j++) {
                                $searchindex = $searcharray[$j];
                                $filter_string .= " OR " . $fields[$searchindex] . " " . $iopt . " " . $irealdata;
                            }
                            $filter_string .= ")";
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . $ioldcon . $idata;
                            $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                            $filter_string .= " AND (" . $fields[$i] . " " . $iopt . " " . $irealdata;
                            $j = 0;
                            for ($j = 0; $j < count($searcharray); $j++) {
                                $searchindex = $searcharray[$j];
                                $filter_string .= " OR " . $fields[$searchindex] . " " . $iopt . " " . $irealdata;
                            }
                            $filter_string .= ")";
                        }
                    } else {
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . $ioldcon . $idata;
                            $filter_string = $fields[$i] . " " . $iopt . " " . $irealdata;
                            if ($parammaster[$i] != ""){
                                $filter_stringmaster = $parammaster[$i] . " " . $iopt . " " . $irealdata;
                            }
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . $ioldcon . $idata;
                            $filter_string .= $icon . $fields[$i] . " " . $iopt . " " . $irealdata;
                            if ($parammaster[$i] != ""){
                                $filter_stringmaster .= $icon . $parammaster[$i] . " " . $iopt . " " . $irealdata;
                            }
                        }
                    }
                } else {
                    $err_string .= "<strong>Error:</strong>while searching.<strong>" . $field_name . "</strong>.<br>";
                    $err_string .= "Description: Type mismatch.<br>";
                }
            //==end $type_field == "type_integer"
            } elseif ($type_field == "type_string") {
                if (($iopt)=="="){
                    $conditionstr = " Like ";
                    	 $istrdata = str_replace("=", "", $istrdata);
                 } else {
                      $conditionstr = $iopt;
                  		 $istrdata = $irealdata;
                  		 $searchstartkey = "";
                  		 $searchendkey   = "";
                 }
                 if ((qssession("students_multisearch_fd" . $i) != "")) {
                        $multisearch = qssession("students_multisearch_fd" . $i);
                        $searcharray = split(",",$multisearch);
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                            $filter_string = "(" . $fields[$i] . $conditionstr . " '" .$searchstartkey.  ereg_replace("'","''",stripslashes($irealdata)). $searchendkey . "'";
                            $j = 0;
                            for ($j = 0; $j < count($searcharray); $j++) {
                                $searchindex = $searcharray[$j];
                                $filter_string .= " OR " . $fields[$searchindex]  . $conditionstr . " '" .$searchstartkey.  ereg_replace("'","''",stripslashes($irealdata)). $searchendkey . "'";
                            }
                            $filter_string .= ")";
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                            $filter_string .= " AND (" . $fields[$i]  . $conditionstr . " '" .$searchstartkey. ereg_replace("'","''",stripslashes($irealdata)) . $searchendkey . "'";
                            $j = 0;
                            for ($j = 0; $j < count($searcharray); $j++) {
                                $searchindex = $searcharray[$j];
                                $filter_string .= " OR " . $fields[$searchindex]  . $conditionstr . " '" .$searchstartkey. ereg_replace("'","''",stripslashes($irealdata)) . $searchendkey . "'";
                            }
                            $filter_string .= ")";
                        }
                    } else {
                        if ($qry_string == "") {
                            $qry_string = "search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $filter_string = $fields[$i]  . $conditionstr . " '" .$searchstartkey. ereg_replace("'","''",stripslashes($irealdata)) . $searchendkey . "'";
                            if ($parammaster[$i] != ""){
                                 $filter_stringmaster = $parammaster[$i]  . $conditionstr . " '" .$searchstartkey. ereg_replace("'","''",stripslashes($irealdata)) . $searchendkey . "'";
                            }
                        } else {
                            $qry_string .= "&search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                            $filter_string .= $icon . $fields[$i]  . $conditionstr . " '" .$searchstartkey. ereg_replace("'","''",stripslashes($irealdata)) . $searchendkey . "'";
                            if ($parammaster[$i] != ""){
                                 $filter_stringmaster .= $icon . $parammaster[$i]  . $conditionstr . " '" .$searchstartkey. ereg_replace("'","''",stripslashes($irealdata)) . $searchendkey . "'";
                            }
                        }
                    }
            //==end $type_field == "type_string"
            } else {
                if ((qssession("students_multisearch_fd" . $i) != "")) {
                    $multisearch = qssession("students_multisearch_fd" . $i);
                    $searcharray = split(",",$multisearch);
                    $irealdata = str_replace("%", "",  $irealdata);
                    if ($qry_string == "") {
                        $qry_string = "search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                        $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                        $filter_string = "(" . $fields[$i] . " = '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        $j = 0;
                        for ($j = 0; $j < count($searcharray); $j++) {
                            $searchindex = $searcharray[$j];
                            $filter_string .= " OR " . $fields[$searchindex] . " = '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        }
                        $filter_string .= ")";
                    } else {
                        $qry_string .= "&search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                        $qry_string .= "&multisearch_fd" . $i . "=" . qssession("students_multisearch_fd" . $i);
                        $filter_string .= " AND (" . $fields[$i] . " = '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        $j = 0;
                        for ($j = 0; $j < count($searcharray); $j++) {
                            $searchindex = $searcharray[$j];
                            $filter_string .= " OR " . $fields[$searchindex] . " = '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        }
                        $filter_string .= ")";
                    }
                } else {
                    if ($qry_string == "") {
                        $qry_string = "search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                        $filter_string = $fields[$i] . " like '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        if ($parammaster[$i] != ""){
                            $filter_stringmaster = $parammaster[$i] . " like '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        }
                    } else {
                        $qry_string .= "&search_fd" . $i . "=" . $ioldcon . urlencode(stripslashes($idata));
                        $filter_string .= $icon . $fields[$i] . " like '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        if ($parammaster[$i] != ""){
                            $filter_stringmaster .= $icon . $parammaster[$i] . " like '" . ereg_replace("'","''",stripslashes($irealdata)) . "'";
                        }
                    }
                }
            } //==end $type_field == "type_unknown
        } //==end if ($meta)
    } //==end if search_fd(n) <> ""
    //==Begin Search between
    if (qssession("students_search_fd_" . $i)) {
        $idata = qssession("students_search_fd_" . $i);
        $idata = str_replace("*", "%", $idata);
        $irealdata = $idata;
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
        } else {
            $iopt = substr($idata, 0, 1);
            if (($iopt == "<") || ($iopt == ">") || ($iopt == "=")) {
                $irealdata = substr($idata,1);
            } else {
                $iopt = "=";
            }
        }
        if ($meta) {
            if ($type_field == "type_datetime") {
                if ((($timestamp = strtotime($irealdata)) !== -1)) {
                    if (($iopt)=="="){
                        $conditionstr = " = ";
                        $istrdata = str_replace("=", "", $istrdata);
                    } else {
                        $conditionstr = $iopt;
                        $istrdata = $irealdata;
                        $searchstartkey = "";
                        $searchendkey   = "";
                    }
                }
                    //Prepare  date format for each item search then convert to compatible format 
                    if (qssession("students_search_fd_" . $i . "_DateFormat") != ""){ 
                      $iDateFormat = qssession("students_search_fd_" . $i . "_DateFormat"); 
                    } else { 
                      $iDateFormat = ""; 
                    } 
                if ($qry_string == "") {
                    $qry_string = "search_fd_" . $i . "=" . $iopt . urlencode(stripslashes($irealdata));
                    $filter_string = $fields[$i]  . $conditionstr . " " . $quotedate .$searchstartkey . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $searchendkey . $quotedate;
                } else {
                    $qry_string .= "&search_fd_" . $i . "=" . $iopt . urlencode(stripslashes($irealdata));
                    $filter_string .= " AND " . $fields[$i]  . $conditionstr . " " . $quotedate . $searchstartkey . qsconvertdate2ansi($irealdata, $iDateFormat, "") . $searchendkey . $quotedate;
                }
            //==end $type_field == "type_datetime"
            } elseif ($type_field == "type_integer") {
                $irealdata = str_replace("%", "", $irealdata);
                if (is_numeric($irealdata)) {
                    if ($qry_string == "") {
                        $qry_string = "search_fd_" . $i . "=" . $iopt . $irealdata;
                        $filter_string = $fields[$i] . " " . $iopt . " " . $irealdata;
                    } else {
                        $qry_string .= "&search_fd_" . $i . "=" . $iopt . $irealdata;
                        $filter_string .= " AND " . $fields[$i] . " " . $iopt . " " . $irealdata;
                    }
                } else {
                    $err_string .= "<strong>Error:</strong>while searching.<strong>" . $field_name . "</strong>.<br>";
                    $err_string .= "Description: Type mismatch.<br>";
                }
            } //==end $type_field == "type_integer"
        } //==end if ($meta)
    } //==end if search_fd_(n) <> "" for between search
    $i++;
} //==end while loop field index
if ($result > 0) {mysql_free_result($result);}
if ($filter_string != "") {
    $filter_string = "(" . $filter_string . ")";
    if (strpos(strtoupper($sql), " WHERE ")) {
         $sql .= " And " . $filter_string;
    }else{
         $sql .= " Where " . $filter_string;
    }
}
$n = 0;
if (qssession("students_students") != "") {
    $parampage = explode("||", qssession("students_students"));
    $n = count($parampage);
}
$current_page = 1;
$page_size  = 10;
if ($n > 0) {
    if ($parampage[0] != "") {
        $current_page = $parampage[0];
    }
    if ($parampage[1] != "") {
        $page_size = $parampage[1];
    }
}
if (qsrequest("page")<>"") {
    $current_page = qsrequest("page");
}
else if (qssession("students_students_PageNumber")) {
    $current_page = qssession("students_students_PageNumber");
}
else {
    $current_page = 1;
}
$_SESSION["students_students_PageNumber"] = $current_page;
if (qsrequest("page_size")<>"") {
    if(qsrequest("page_size") != $page_size) {
        $current_page = 1;
    }
    $page_size = qsrequest("page_size");
}
$_SESSION["students_students"] = $current_page . "||" . $page_size;
if (qsrequest("sortfield") != "") {
    $_SESSION["students_sortfield"] = qsrequest("sortfield");
}
if (qsrequest("sortby") != "") {
    $_SESSION["students_sortby"] = qsrequest("sortby");
}
if (qssession("students_sortfield")) {
    $sql = $sql . " " . $SQL_GroupOnly;
    $sql = $sql . " Order By " . stripslashes(qssession("students_sortfield")) . " " . stripslashes(qssession("students_sortby"));
    $sortstring = "&sortfield=" . qssession("students_sortfield") . "&sortby="  . qssession("students_sortby");
} else {
    $sql = $sql . " " . $sql_ext;
}
?>
<HTML>

<HEAD>

<Title>Students Data</Title>

<link rel="stylesheet" type="text/css" href="students.css">


<script type="text/javascript" src="./js/yahoo-min.js" ></script>
<script type="text/javascript" src="./js/dom-min.js" ></script>
<script type="text/javascript" src="./js/event-min.js" ></script>

<script type="text/javascript">

  
  YAHOO.util.Event.onDOMReady( function() { qsPageOnLoadController(); } );  

</script>



	<link rel="stylesheet" type="text/css" href="./css/ContentLayout.css"></link>


<!-- END OF STD-Loader.txt -->


<script type="text/javascript">

var qsPageItemsCount = 9
var _Studid                                   = 0;
var _Name                                     = 1;
var _Course                                   = 2;
var _Year                                     = 3;
var _Sec                                      = 4;
var _Password                                 = 5;
var _Sy                                       = 6;
var _Edit                                     = 7;
var _Delete                                   = 8;

// Declare Fields Prompts
var fieldPrompts = [];
fieldPrompts[_Studid] = "Studid";
fieldPrompts[_Name] = "Name";
fieldPrompts[_Course] = "Course";
fieldPrompts[_Year] = "Year";
fieldPrompts[_Sec] = "Sec";
fieldPrompts[_Password] = "Password";
fieldPrompts[_Sy] = "Sy";
fieldPrompts[_Edit] = "Edit";
fieldPrompts[_Delete] = "Delete";

// Declare Fields Technical Names
var fieldTechNames = [];
fieldTechNames[_Studid] = "Studid";
fieldTechNames[_Name] = "Name";
fieldTechNames[_Course] = "Course";
fieldTechNames[_Year] = "Year";
fieldTechNames[_Sec] = "Sec";
fieldTechNames[_Password] = "Password";
fieldTechNames[_Sy] = "Sy";
fieldTechNames[_Edit] = "Edit";
fieldTechNames[_Delete] = "Delete";

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
}

</script>



<script type="text/javascript">


function qsAssignPageItemEvents() {
}

</script>





</style>
 



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

   return true;                                        
}                                                      











</script>

<meta name="generator" content="dbQwikSite Ecommerce"><meta name="dbQwikSitePE" content="QSFREEPE">

</HEAD>

<BODY>

<SCRIPT language=JavaScript>
function cell_over(cell, classname) {
    if (document.all || document.getElementById) {
        cell.classBackup = cell.className;
        cell.className   = classname;
    }
}
function cell_out(cell)
{
    if (document.all || document.getElementById) {
        cell.className   = cell.classBackup;
    }
}
</SCRIPT>


<Center>
<center><hr />
  <span class="style1"><font size="5">
Students Data
  </font></span>
  <hr /></center><br>



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


<?php
$result = mysql_query($sql)
          or die("Invalid query");
  if (!$result){
 }
$num_rows = mysql_num_rows($result);
$page_count = ceil($num_rows/$page_size);
if ($current_page > $page_count) { $current_page = 1; }
if ($current_page < 1) { $current_page = 1; }
if ($page_count < 1) { $page_count = 1; }
if ($filter_string !=""){
  print "Found ".$num_rows. " record(s)";
  print "<br>";
}
?>


<?php
if ($qry_string != "") {
  $navqry_string = "&" . $qry_string;
} else {
  $navqry_string = "";
}
print "<table height=\"30\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
print "<tr align=\"center\" valign=\"middle\">";
print "<td width=\"35\" align=\"center\"><A HREF=\"students_control_panel.html\"><img src=\"images/bt_controlpanelgroup.gif\" border=\"0\" align=\"absmiddle\" title=\"Back to Control Panel\"></A></td><td width=\"12\" align=\"center\"><IMG src=\"images/bt_qsbetween.gif\" border=\"0\"></td>";
print "<form action=\"students.php\" method=\"post\" name=\"QSSelectPage\">";
print "<td width=\"35\" align=\"center\"><A HREF=\"students_add.php?" . $qry_string . "\"><img src=\"images/bt_qsadd_new.gif\" border=\"0\" align=\"absmiddle\" title=\"Add New\"></A></td><td width=\"12\" align=\"center\"><IMG src=\"images/bt_qsbetween.gif\" border=\"0\"></td>";
if ($current_page > 1) {
    print "<td width=\"35\" align=\"center\"><A HREF=\"students.php?page=" . ($current_page - 1) . "\"><img src=\"images/bt_qsback.gif\" border=\"0\" align=\"absmiddle\" title=\"Previous\"></A></td><td width=\"8\"></td>";
} else {
    print "<td width=\"35\" align=\"center\"><img src=\"images/bt_qsback_inact.gif\" border=\"0\" align=\"absmiddle\" title=\"Previous\"></td><td width=\"8\"></td>";
}
print "<td width=\"35\" align=\"center\"><select name=\"page\"  onChange=\"window.location='students.php?page=' + this.value; \">";
for ($i = 1; $i <= $page_count; $i++) {
    if ($i == $current_page) {
        print "<option value=\"". $i . "\" selected>" . ($i) . "</option>";
    } else {
        print "<option value=\"". $i . "\">" . ($i) . "</option>";
    }
}
print "</select></td><td width=\"8\"></td>";
if ($current_page < $page_count) {
    print "<td width=\"35\" align=\"center\"><A HREF=\"students.php?page=" . ($current_page + 1) . "\"><img src=\"images/bt_qsnext.gif\" border=\"0\" align=\"absmiddle\" title=\"Next\"></A></td><td width=\"8\"></td>";
} else {
    print "<td width=\"35\" align=\"center\"><img src=\"images/bt_qsnext_inact.gif\" border=\"0\" align=\"absmiddle\" title=\"Next\"></td><td width=\"8\"></td>";
}
print "<td width=\"35\" align=\"center\"><A HREF=#BOTTOM><img src=\"images/bt_qsbottom.gif\" border=\"0\" align=\"absmiddle\" title=\"Bottom\"></A></td><td width=\"8\"></td>";
print "</form>";
print "</tr>";
print "</table>";
print "<br>";
?>

<?php
if ($num_rows > 0) {
?>

<Table  id="masterDataTable"  Border="0" Cellpadding="2" Cellspacing="1"BgColor="#D4D4D4">

<tr>


<?php
$nextsortasc = qssortasc(qssession("students_sortfield"), $fields[0], qssession("students_sortby"),  "Sort Ascending");
$nextsortdesc = qssortdesc(qssession("students_sortfield"), $fields[0], qssession("students_sortby"),  "Sort Descending");
?>
<td id="Studid_caption_cell" class="ThRows"  NOWRAP ><a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[0]));?>&sortby=ASC<?php print $navqry_string; ?>"><?php print $nextsortasc; ?></a>
&nbsp;<span id="Studid_caption_div">Studid</span>&nbsp;
<a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[0]));?>&sortby=DESC<?php print $navqry_string; ?>"><?php print $nextsortdesc; ?></a></td>
<?php
$nextsortasc = qssortasc(qssession("students_sortfield"), $fields[1], qssession("students_sortby"),  "Sort Ascending");
$nextsortdesc = qssortdesc(qssession("students_sortfield"), $fields[1], qssession("students_sortby"),  "Sort Descending");
?>
<td id="Name_caption_cell" class="ThRows"  NOWRAP ><a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[1]));?>&sortby=ASC<?php print $navqry_string; ?>"><?php print $nextsortasc; ?></a>
&nbsp;<span id="Name_caption_div">Name</span>&nbsp;
<a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[1]));?>&sortby=DESC<?php print $navqry_string; ?>"><?php print $nextsortdesc; ?></a></td>
<?php
$nextsortasc = qssortasc(qssession("students_sortfield"), $fields[2], qssession("students_sortby"),  "Sort Ascending");
$nextsortdesc = qssortdesc(qssession("students_sortfield"), $fields[2], qssession("students_sortby"),  "Sort Descending");
?>
<td id="Course_caption_cell" class="ThRows"  NOWRAP ><a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[2]));?>&sortby=ASC<?php print $navqry_string; ?>"><?php print $nextsortasc; ?></a>
&nbsp;<span id="Course_caption_div">Course</span>&nbsp;
<a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[2]));?>&sortby=DESC<?php print $navqry_string; ?>"><?php print $nextsortdesc; ?></a></td>
<?php
$nextsortasc = qssortasc(qssession("students_sortfield"), $fields[3], qssession("students_sortby"),  "Sort Ascending");
$nextsortdesc = qssortdesc(qssession("students_sortfield"), $fields[3], qssession("students_sortby"),  "Sort Descending");
?>
<td id="Year_caption_cell" class="ThRows"  NOWRAP ><a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[3]));?>&sortby=ASC<?php print $navqry_string; ?>"><?php print $nextsortasc; ?></a>
&nbsp;<span id="Year_caption_div">Year</span>&nbsp;
<a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[3]));?>&sortby=DESC<?php print $navqry_string; ?>"><?php print $nextsortdesc; ?></a></td>
<?php
$nextsortasc = qssortasc(qssession("students_sortfield"), $fields[4], qssession("students_sortby"),  "Sort Ascending");
$nextsortdesc = qssortdesc(qssession("students_sortfield"), $fields[4], qssession("students_sortby"),  "Sort Descending");
?>
<td id="Sec_caption_cell" class="ThRows"  NOWRAP ><a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[4]));?>&sortby=ASC<?php print $navqry_string; ?>"><?php print $nextsortasc; ?></a>
&nbsp;<span id="Sec_caption_div">Sec</span>&nbsp;
<a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[4]));?>&sortby=DESC<?php print $navqry_string; ?>"><?php print $nextsortdesc; ?></a></td>
<?php
$nextsortasc = qssortasc(qssession("students_sortfield"), $fields[5], qssession("students_sortby"),  "Sort Ascending");
$nextsortdesc = qssortdesc(qssession("students_sortfield"), $fields[5], qssession("students_sortby"),  "Sort Descending");
?>
<td id="Password_caption_cell" class="ThRows"  NOWRAP ><a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[5]));?>&sortby=ASC<?php print $navqry_string; ?>"><?php print $nextsortasc; ?></a>
&nbsp;<span id="Password_caption_div">Password</span>&nbsp;
<a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[5]));?>&sortby=DESC<?php print $navqry_string; ?>"><?php print $nextsortdesc; ?></a></td>
<?php
$nextsortasc = qssortasc(qssession("students_sortfield"), $fields[6], qssession("students_sortby"),  "Sort Ascending");
$nextsortdesc = qssortdesc(qssession("students_sortfield"), $fields[6], qssession("students_sortby"),  "Sort Descending");
?>
<td id="Sy_caption_cell" class="ThRows"  NOWRAP ><a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[6]));?>&sortby=ASC<?php print $navqry_string; ?>"><?php print $nextsortasc; ?></a>
&nbsp;<span id="Sy_caption_div">Sy</span>&nbsp;
<a href="students.php?sortfield=<?php print urlencode(stripslashes($fields[6]));?>&sortby=DESC<?php print $navqry_string; ?>"><?php print $nextsortdesc; ?></a></td>
<td id="Edit_caption_cell" class="ThRows"  NOWRAP><span id="Edit_caption_div">Edit</span></td>
<td id="Delete_caption_cell" class="ThRows"  NOWRAP><span id="Delete_caption_div">Delete</span></td><?php
?>

</tr>

<?php
$rowcount = 0;
$current_row = ($current_page - 1)*$page_size;
if (($num_rows > 0) && ($current_row < $num_rows)){
    mysql_data_seek($result, $current_row);
}
while (($row = mysql_fetch_array($result)) && ($rowcount < $page_size)) {

  $intColCount = 0;

    if (($rowcount%2) == 0) {
        $css_class = "\"TrOdd\"";
    } else {
        $css_class = "\"TrRows\"";
    }
    print "<tr class=" . $css_class . " onmouseover=\"cell_over(this, 'TrHover')\"  onmouseout=\"cell_out(this)\">";

   $intColCount++;
   $intColIndex = 0;

    $cellvalue = "" . $row[0] . "";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Default >";
    print $cellvalue;
    print "</td>";
   $intColCount++;
   $intColIndex = 1;

    $cellvalue = "" . $row[1] . "";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Default >";
    print $cellvalue;
    print "</td>";
   $intColCount++;
   $intColIndex = 2;

    $cellvalue = "" . $row[2] . "";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Default >";
    print $cellvalue;
    print "</td>";
   $intColCount++;
   $intColIndex = 3;

    $cellvalue = "" . $row[3] . "";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Default >";
    print $cellvalue;
    print "</td>";
   $intColCount++;
   $intColIndex = 4;

    $cellvalue = "" . $row[4] . "";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Default >";
    print $cellvalue;
    print "</td>";
   $intColCount++;
   $intColIndex = 5;

    $cellvalue = "" . $row[5] . "";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Default >";
    print $cellvalue;
    print "</td>";
   $intColCount++;
   $intColIndex = 6;

    $cellvalue = "" . $row[6] . "";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Default >";
    print $cellvalue;
    print "</td>";
   $intColCount++;
   $intColIndex = 7;

    $cellvalue = "<img src=\"" . "./images/bt_edit.gif" . "\" border=\"0\"  title=\"Edit\" onerror=\"this.onerror=null;this.src='./images/qs_nopicture.gif';\" >";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Center >";
    print "<a href=\"" . "./students_edit.php?" . ""."currentrow_fd0=" . urlencode($row[0]) . "" . "\" >" . $cellvalue . "</a>";
    print "</td>";
   $intColCount++;
   $intColIndex = 8;

    $cellvalue = "<img src=\"" . "./images/bt_delete.gif" . "\" border=\"0\"  title=\"Delete\" onerror=\"this.onerror=null;this.src='./images/qs_nopicture.gif';\" >";
    if ($cellvalue != "") {
        $cellvalue = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$cellvalue);
    }
    else { 
        $cellvalue = "&nbsp;";
    }

    print "<td align=Center >";
    print "<a href=\"" . "./students_delete.php?" . ""."currentrow_fd0=" . urlencode($row[0]) . "" . "\" >" . $cellvalue . "</a>";
    print "</td>";

    print "</tr>";

  $rowcount = $rowcount + 1;
}//end while
?>

    </Table>

  <br>

<?php
}else{
?>

<?php
if ($filter_string != ""){
?><Table Border="0" Cellspacing="1" bgcolor="#CCCCCC" >
  <tr>
    <td height="80" align="Default" bgcolor="#FFFFFF">
      <font color="#000099" size="2">
        No record matched your search criteria.
      </font>
    </td>
  </tr>
</Table><br>

<?php
}else{
 ?><Table Border="0" Cellspacing="1" bgcolor="#CCCCCC" >
  <tr>
    <td height="80" align="Default" bgcolor="#FFFFFF">
      <font color="#000099" size="2">
        No record found.
      </font>
    </td>
  </tr>
</Table><br>

<?php
}
}
if ($qry_string != "") {
  $navqry_string = "&" . $qry_string;
} else {
  $navqry_string = "";
}
print "<table height=\"30\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
print "<tr align=\"center\" valign=\"middle\">";
print "<td width=\"35\" align=\"center\"><A HREF=\"students_control_panel.html\"><img src=\"images/bt_controlpanelgroup.gif\" border=\"0\" align=\"absmiddle\" title=\"Back to Control Panel\"></A></td><td width=\"12\" align=\"center\"><IMG src=\"images/bt_qsbetween.gif\" border=\"0\"></td>";
print "<form action=\"students.php\" method=\"post\" name=\"QSSelectPage\">";
print "<td width=\"35\" align=\"center\"><A HREF=\"students_add.php?" . $qry_string . "\"><img src=\"images/bt_qsadd_new.gif\" border=\"0\" align=\"absmiddle\" title=\"Add New\"></A></td><td width=\"12\" align=\"center\"><IMG src=\"images/bt_qsbetween.gif\" border=\"0\"></td>";
if ($current_page > 1) {
    print "<td width=\"35\" align=\"center\"><A HREF=\"students.php?page=" . ($current_page - 1) . "\"><img src=\"images/bt_qsback.gif\" border=\"0\" align=\"absmiddle\" title=\"Previous\"></A></td><td width=\"8\"></td>";
} else {
    print "<td width=\"35\" align=\"center\"><img src=\"images/bt_qsback_inact.gif\" border=\"0\" align=\"absmiddle\" title=\"Previous\"></td><td width=\"8\"></td>";
}
print "<td width=\"35\" align=\"center\"><select name=\"page\"  onChange=\"window.location='students.php?page=' + this.value; \">";
for ($i = 1; $i <= $page_count; $i++) {
    if ($i == $current_page) {
        print "<option value=\"". $i . "\" selected>" . ($i) . "</option>";
    } else {
        print "<option value=\"". $i . "\">" . ($i) . "</option>";
    }
}
print "</select></td><td width=\"8\"></td>";
if ($current_page < $page_count) {
    print "<td width=\"35\" align=\"center\"><A HREF=\"students.php?page=" . ($current_page + 1) . "\"><img src=\"images/bt_qsnext.gif\" border=\"0\" align=\"absmiddle\" title=\"Next\"></A></td><td width=\"8\"></td>";
} else {
    print "<td width=\"35\" align=\"center\"><img src=\"images/bt_qsnext_inact.gif\" border=\"0\" align=\"absmiddle\" title=\"Next\"></td><td width=\"8\"></td>";
}
print "<td width=\"35\" align=\"center\"><A HREF=#TOP><img src=\"images/bt_qstop.gif\" border=\"0\" align=\"absmiddle\" title=\"Top\"></A></td><td width=\"8\"></td>";
print "</form>";
print "</tr>";
print "</table>";
print "<br>";
?>

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
 
<a href="home.php">Home</a>
</Center>


</BODY>
<div id="Footer" align="center"> 
 <p>&copy; <?php echo date('Y');?>.All rights reserved E-voting system</p>
        <p>Designed & Developed by  <b>Harjeet kaur</b></p>
		
  </div>
</HTML>

