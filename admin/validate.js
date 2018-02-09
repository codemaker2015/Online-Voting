function Trim(s) {
  var temp = " ";
  var i = 0;
  while ((temp == " ") && (i <= s.length)) {
    temp = s.charAt(i);
    i++;
  }
  s = s.substring(i - 1, s.length);
  return(s);
}

function replaceAll(str, from, to) {
  var idx;
  idx = -1;
  if ((str != null) && (str != "")) {idx = str.indexOf(from);}
  while (idx > -1) {
  	str = str.replace(from, to);
		idx = str.indexOf(from);
  }
  return str;
}


function RequiredField(val) {
    var value = Trim(val);
    if(value=="") {
        return false;
    }
    return true;
}


function EmailValidate(val) {
    if(val==""){
        return true;
        //Can be return false; if you want to validate "cannot be blank value"
    }
    var value = Trim(val);
    var emailPat=/^(.+)@(.+)$/;
    var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]";
    var validChars="\[^\\s" + specialChars + "\]";
    var quotedUser="(\"[^\"]*\")";
    var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
    var atom=validChars + '+';
    var word="(" + atom + "|" + quotedUser + ")";
    var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
    var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
    var matchArray=value.match(emailPat);
    if (matchArray==null) {
        return false;
    }
    var user=matchArray[1];
    var domain=matchArray[2];
    if (user.match(userPat)==null){
        return false;
    }
    return true;
}


function LengthStr(val,op,size1,size2) {
  if(val==""){
    return true;
  }
  var value = Trim(val);
  if((op==">") && (value.length>size1)) {
    return true;
  }
  if((op==">=") && (value.length>=size1)) {
    return true;
  }
  if((op=="<") && (value.length<size1)) {
    return true;
  }
  if((op=="<=") && (value.length<=size1)) {
    return true;
  }
  if((op=="=") && (value.length==size1)) {
    return true;
  }
  if((op=="!=") && (value.length!=size1)) {
    return true;
  }
  if((op=="><") && (value.length>=size1) && (value.length<=size2)) {
    return true;
  }
    return false;
}


function NumberValidate(val) {
    if(val==""){
        return true;
    }
    var value = val;
    value = replaceAll(value, ',','');
    if (!isFinite(value)) {
        return  false;
    }
    return true;
}


function IntValidate(val) {
    if(val==""){
        return true;
    }
    var value = val;
    value = replaceAll(value, ',','');
    if (!isFinite(value)) {
        return  false;
    }
    value = value.split('.').length-1 ;
    if (value>0) {
        return false;
    }
    return true;
}


function RangeNumber(val,op,std1,std2){
	if(val==""){
		return true ;
	}
	if((op=="=") && (val==std1)){
		return true ;
	}
	if((op==">=") && (val>=std1)){
		return true ;
	}
	if((op==">") && (val>std1)){
		return true ;
	}
	if((op=="<=") && (val<=std1)){
		return true ;
	}
	if((op=="<") && (val<std1)){
		return true ;
	}
	if((op=="!=") && (val!=std1)){
		return true ;
	}
	if((op=="><") && (val>=std1)&& (val<=std2)){
		return true ;
	}
	return false ;
}

//********** Delete Page Function ***************
window.document.onload= setInterval ("SetPosToCenter(divDlg)", 250);
var param;
var divDlg;
function getStyle(el, style) {
   if (el == null) { return; }
   var value = el.style[style];
   if(!value){
      if(document.defaultView) {
          value = document.defaultView.getComputedStyle(el, "").getPropertyValue(style);
	    }else if(el.currentStyle) {
          value = el.currentStyle[style];
	    }
   }
   return value;
}
//Set Position of delete dialog to screen center
function SetPosToCenter(sDivDlg){
   var divW, divH, windowW, windowH;
   if (sDivDlg == null) {
      return; 
   }
   divW = parseInt(getStyle(sDivDlg, 'width'));
   divH = parseInt(getStyle(sDivDlg, 'height'));
   windowW = parseInt(window.document.body.clientWidth);
   windowH = parseInt(window.document.body.clientHeight);
   sDivDlg.style.left = parseInt((windowW/2) - (divW/2) + window.document.body.scrollLeft);
   sDivDlg.style.top  = parseInt((windowH/2) - (divH/2) + window.document.body.scrollTop);
}
//Go to delete page with parameter
function DeleteRecord(sMessage) {
   divDlg = document.getElementById("divDeleteDlg");
   if (sMessage == "Y") {
      window.location = param;
   } else { 
      divDlg.style.display = "none"; 
   }
}
//Show delete dialog and assign parameter 
function DeleteData(sParam) {
   divDlg = document.getElementById("divDeleteDlg");
   SetPosToCenter(divDlg);
   if (divDlg.style.display == "none") {
      param = sParam;
      divDlg.style.display = "block"; 
   }
}
