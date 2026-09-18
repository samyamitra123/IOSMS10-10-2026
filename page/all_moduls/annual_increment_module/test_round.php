<?php 
$input=120.99;
$a=$input%10;
if($a==0)
{
	$whole=floor($input);
	$decimal=$input-$whole;
	
	if($decimal<=.50)
	{
		$number=$whole;
	}
	else
	{
		$number = ceil($input / 10) * 10;
	}
}
else
{
	$number = ceil($input / 10) * 10;
}
echo $number;