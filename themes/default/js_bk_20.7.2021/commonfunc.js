function keyRestrict(e, validchars){
	var key='', keychar='';
	key = getKeyCode(e);
	if (key == null) return true;
		keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();
	validchars = validchars.toLowerCase();
	if (validchars.indexOf(keychar) != -1)
		return true;
	if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
		return true;
	return false;
}
function getKeyCode(e){
	if (window.event)
		return window.event.keyCode;
	else if (e)
		return e.which;
	else
		return null;
}
function trimString (str){
  str = this != window? this : str;
  return str.replace(/^\s+/g, '').replace(/\s+$/g, '');
}
function focusFirst(idVal){	
	if(trimString(idVal.value)=='First Name'){
		idVal.value='';
		idVal.style.color='#005279';
	}
}
function focusMiddle(idVal){
	if(trimString(idVal.value)=='Middle Name'){
		idVal.value='';
		idVal.style.color='#005279';
	}
}
function focusLast(idVal){
	if(trimString(idVal.value)=='Last Name'){
		idVal.value='';
		idVal.style.color='#005279';
	}
}
function blurFirst(idVal){
	if (trimString(idVal.value)=='' || trimString(idVal.value)=='First Name'){
		idVal.value='First Name'; 
		idVal.style.color='#A2A2A2';
	}
}
function blurMiddle(idVal){
	if (trimString(idVal.value)=='' || trimString(idVal.value)=='Middle Name'){
		idVal.value='Middle Name'; 
		idVal.style.color='#A2A2A2';
	}
}
function blurLast(idVal){
	if (trimString(idVal.value)=='' || trimString(idVal.value)=='Last Name'){
		idVal.value='Last Name'; 
		idVal.style.color='#A2A2A2';
	}
}
function textCounter(field,entr,remr,maxlimit){
	if (field.value.length > maxlimit) // if too long...trim it!			
		field.value = field.value.substring(0, maxlimit);
		document.getElementById(entr).innerHTML=field.value.length;							
		document.getElementById(remr).innerHTML=maxlimit - field.value.length;			
}

function stateShare(field){
	var val=(((0.25)*parseFloat(field))/0.75);
	return val.toFixed(2);
}
function parseDate(input){
  var parts = input.match(/(\d+)/g);
  return new Date(parts[0], parts[1]-1, parts[2]);
}