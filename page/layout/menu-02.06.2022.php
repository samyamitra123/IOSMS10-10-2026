 <?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/
?>
 <style>
 th:hover {
  background-color: yellow;
}

</style>

 <nav class="navbar navbar-default" id="navMenu">
 <div class="container-fluid"> 
 <table width="100%" style="text-align: center;" >
	<th><a href="<?= $config['base_url']; ?>">Home</a></th>
    <th><a href="<?= $config['base_url']; ?>page/under_construction.php">About Us</a></th>
	<th><a href="<?= $config['base_url']; ?>page/under_construction.php">Guideline</a></th>
	<th><a href="<?= $config['base_url']; ?>page/under_construction.php">FAQ</a></th>
	<th><a href="<?= $config['base_url']; ?>page/under_construction.php">RTI</a></th>
            
 </table>
 </div>
 </nav>