<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<script>

if(trim($('#ifsc_code'+new_id).val())==''){
					alert('Please enter ifsc code.');
					$('#ifsc_code'+new_id).focus();
					event.preventDefault();
					return false;
				}
				
				if(trim($('#basic'+new_id).val())==''){
					alert('Please enter basic.');
					$('#basic'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(value_test.test($('#basic'+new_id).val())==false){
					alert('Please enter valid basic.');
					$('#basic'+new_id).focus();
					event.preventDefault();
					return false;
				}
				
				if(trim($('#d_pay'+new_id).val())==''){
					alert('Please enter D.PAY.');
					$('#d_pay'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#da'+new_id).val())==''){
					alert('Please enter DA.');
					$('#da'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#hra'+new_id).val())==''){
					alert('Please enter HRA');
					$('#hra'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#ma'+new_id).val())==''){
					alert('Please enter MA');
					$('#ma'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#ca'+new_id).val())==''){
					alert('Please enter Conv Allow.');
					$('#ca'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#pf'+new_id).val())==''){
					alert('Please enter PF.');
					$('#pf'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#pt'+new_id).val())==''){
					alert('Please enter P.TAX.');
					$('#pt'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#itax'+new_id).val())==''){
					alert('Please enter I.TAX.');
					$('#itax'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if(trim($('#net'+new_id).val())=='' || value_test.test($('#net'+new_id).val())==false || $('#net'+new_id).val()<0)
				{
					alert('net amount is not valid');
					$('#net'+new_id).focus();
					event.preventDefault();
					return false;				
				}

</script>

</head>

<body>
</body>
</html>