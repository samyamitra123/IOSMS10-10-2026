<?
function qry_str_encode($input)
{
 
    $inputlen = strlen($input);// Counts number characters in string $input
    $randkey = rand(3, 7); // Gets a random number between 1 and 9
 
    $i = 0;
    while ($i < $inputlen)
    {
 
        $inputchr[$i] = (ord($input[$i]) - $randkey);//encrpytion 
 
        $i++; // For the loop to function
    }
 
//Puts the $inputchr array togtheir in a string with the $randkey add to the end of the string
    $encrypted = implode('.', $inputchr) . '.' . (ord($randkey)+50);
    return $encrypted;
}

/********************** Function for Data Decryption *************************************/
function qry_str_decode($input)
{
  $input_count = strlen($input);
 
  $dec = explode(".", $input);// splits up the string to any array
  $x = count($dec);
  $y = $x-1;// To get the key of the last bit in the array 
 
  $calc = $dec[$y]-50;
  $randkey = chr($calc);// works out the randkey number
 
  $i = 0;
 
   while ($i < $y)
  {
 
    $array[$i] = $dec[$i]+$randkey; // Works out the ascii characters actual numbers
    $real .= chr($array[$i]); //The actual decryption
 
    $i++;
  };
 
$input = $real;
return $input;
}
?>