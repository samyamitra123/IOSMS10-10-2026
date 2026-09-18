<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

?>
 <style>
 th:hover {
  background-color: yellow;
}
/*//-----*/

.nav-link.text-dark:hover {
    color: #999 !important;
}

.custom-badge {
    position: relative;
    top: -7px;
    right: -13px;
    font-size: .7em;
}
.container-nav {
    max-width: 1140px;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

@media (max-width: 767.98px) { 
    .container-nav {
        max-width: 100%;
        padding-right: 0;
        padding-left: 0;
    }
    .topheader {
        padding-right: 15px;
        padding-left: 15px;
    }
}
            .notice-head{max-height: 40px;}
            .notice-head h2{
                            width: 15%;
                            font-size: 20px;
                            background-color: #f70303;
                            /* display: inline-flex; */
                            float: left;
                            height: 40px;
                            line-height: 40px;
                            color: #ffffff;
                            text-align: center;
                           }
            .notice-head marquee{
                                    float: left;
                                    width: 85%;
                                    background-color: #0d6efd;
                                    color: #fff;
                                    min-height: 40px;
                                    line-height: 40px;
                                    font-size: 22px;
                               }
/*//---    --//*/
</style>

 <!--<nav class="navbar navbar-default" id="navMenu">
 <div class="container-fluid"> 
 <table width="100%" style="text-align: center;" >
	<th><a href="<?= $config['base_url']; ?>">Home</a></th>
    <th><a href="<?= $config['base_url']; ?>page/under_construction.php">About Us</a></th>
	<th><a href="<?= $config['base_url']; ?>page/under_construction.php">Guideline</a></th>
	<th><a href="<?= $config['base_url']; ?>page/faq.php">FAQ</a></th>
	<th><a href="<?= $config['base_url']; ?>page/under_construction.php">RTI</a></th>
            
 </table>
 </div>
 </nav>-->
 <div class="bottom bg-light sticky-top shadow-sm">
        <div class="container-nav">
            <ul class="nav nav-bottom nav-fill">
                <li class="nav-item">
                    <a class="nav-link bg-primary text-white" href="<?= $config['base_url']; ?>"><i class="fa fa-home"></i> <span class="d-none d-sm-inline-block d-md-inline-block">Home</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="<?= $config['base_url']; ?>page/under_construction.php""><i class="fa fa-android"></i> <span class="d-none d-sm-inline-block d-md-inline-block">About Us</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="<?= $config['base_url']; ?>page/under_construction.php"><i class="fa fa-plus-circle"></i> <span class="d-none d-sm-inline-block d-md-inline-block">Guideline</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="<?= $config['base_url']; ?>page/faq.php"> <i class="fa fa-bell"></i> <span class="d-none d-sm-inline-block d-md-inline-block">FAQ</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="<?= $config['base_url']; ?>page/under_construction.php"><i class="fas fa-cog"></i> <span class="d-none d-sm-inline-block d-md-inline-block">RTI</span></a>
                </li>
            </ul>

        </div>
    </div>
    <div class="notice-head">
       <h2>Important Notice</h2>
       <marquee onmouseout="this.start();" onmouseover="this.stop();" scrolldelay="30" scrollamount="10" id="headlines">
           <img src="/themes/default/image/jaihind.png" style="vertical-align: text-top; height: 30px; width: auto;">
          Please Check Your NGIPF A/C No from approver login. Thank You! IT And Statistical Cell, Panchayat & Rural Dev. Dept., Govt. of West Bengal
           <img src="/themes/default/image/jaihind.png" style="vertical-align: text-top; height: 30px; width: auto;">
       </marquee>
   </div> 
   <br clear="all" />