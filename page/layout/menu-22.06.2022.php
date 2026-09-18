 <?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

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
	<th><a href="<?= $config['base_url']; ?>page/faq.php">FAQ</a></th>
	<th><a href="<?= $config['base_url']; ?>page/under_construction.php">RTI</a></th>
            
 </table>
 </div>
 </nav>