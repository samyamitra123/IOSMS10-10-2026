<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
echo "dfadffd";exit;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; 
 connect-src 'self'; 
 form-action 'self'; frame-ancestors 'none'; ");

 header("Strict-Transport-Security: max-age=63072000");*/
ob_start();
session_start();
require '../includes/config/config.php';
require '../includes/config/database.config.php';
require '../includes/library/database.class.php';
require '../includes/library/cryptography.class.php';
$obj_crpto = new cryptography();
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}
else
{
	
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

$num_rec_per_page=5;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 

 $start_from = ($page-1) * $num_rec_per_page; 


$db=new database();
$notice=$db->fetch_table("select  date(upload.date),upload.page_title,upload.meta_description,upload.upload_id_pk,ucategory.category,upload_category_id_pk
from prd_upload as upload
inner join prd_upload_category as ucategory
on ucategory.upload_category_id_pk=upload.upload_category_id_fk
where upload.upload_category_id_fk=ucategory.upload_category_id_pk AND upload.flag=1 AND ucategory.flag=1 AND ucategory.upload_category_id_pk='1'
order by upload.date DESC LIMIT '".$num_rec_per_page."' offset '".$start_from."'");

$news=$db->fetch_table("select  date(upload.date),upload.page_title,upload.meta_description,upload.upload_id_pk,ucategory.category,upload_category_id_pk
from prd_upload as upload
inner join prd_upload_category as ucategory
on ucategory.upload_category_id_pk=upload.upload_category_id_fk
where upload.upload_category_id_fk=ucategory.upload_category_id_pk AND upload.flag=1 AND ucategory.flag=1 AND ucategory.upload_category_id_pk='2'
order by upload.date DESC LIMIT '".$num_rec_per_page."' offset '".$start_from."'");

$count_total=$db->fetch_table("select count(*) as total from prd_upload where upload_category_id_fk='".$obj_crpto->decode($_REQUEST['type'],4)."' AND flag=1");
//$count_news=$db->fetch_table("select count(*) as total from prd_upload where upload_category_id_fk='2' AND flag=1");
$total=$count_total[0]['total'];
//$total_news=$count_news[0]['total'];
$total_pages = ceil($total / $num_rec_per_page);
//$total_pages_news = ceil($total_news / $num_rec_per_page); 
?>

<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
.school table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
}
.school .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school .action .ui-widget{
	font-size: 11px;
}
.school .action{
	text-align: center;
}
.school .action .ui-button .ui-button-text{
	padding: 5px 10px;
}
</style>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>
<div class="content">
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<? if(trim($obj_crpto->decode($_REQUEST['type'],4))==1){ ?>
<h1 class="heading">Notices</h1>
<? } else if(trim($obj_crpto->decode($_REQUEST['type'],4))==2){ ?>
<h1 class="heading">News & Events</h1>
<? } ?>
<div class="border"></div>
<br  /><br  />
<div class="emplist">
<div class="school">
<div class="table-responsive">
<? if(trim($obj_crpto->decode($_REQUEST['type'],4))==1){ ?>
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Date</th>
<th>Notices</th>
</tr>
<? $cnt_notice=1; if(count($notice)){ foreach($notice as $key){ ?>
<tr>
<td><?= $cnt_notice; ?></td>
<td><?= dateshow($key['date']); ?></td>
<td><a style="font-weight:bold" href="<?= $config['base_url']?>page/download.php?upload_id=<?= $key['upload_id_pk']?>&type=<?= $key['upload_category_id_pk']?>"><?= $key['page_title']?><img src="<?= $config['base_url']?>themes/default/image/new_animated.gif" style="vertical-align: text-top;"  /></a></td>
</tr>
<? $cnt_notice++; } } ?>
</table>
<? }  
else if($obj_crpto->decode($_REQUEST['type'],4)==2){ ?>
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Date</th>
<th>News & Events</th>
</tr>
<? $cnt_news=1; if(count($news)){ foreach($news as $key){ ?>
<tr>
<td><?= $cnt_news; ?></td>
<td><?= dateshow($key['date']); ?></td>
<td><a style="font-weight:bold" href="<?= $config['base_url']?>page/download.php?upload_id=<?= $key['upload_id_pk']?>&type=<?= $key['upload_category_id_pk']?>"><?= $key['page_title']?><img src="<?= $config['base_url']?>themes/default/image/new_animated.gif" style="vertical-align: text-top;"  /></a></td>
</tr>
<? $cnt_news++; } } ?>
</table>
<? }?>
</div>
</div>
</div>
<div align="center">
<ul class="pagination" style="alignment-adjust:central">
<? if($total_pages!=1){ for ($i=1; $i<=$total_pages; $i++) {  ?>
<li><a href="<?= $config['base_url']?>page/view_all.php?page=<?=$i?>&type=<?= $_REQUEST['type']?>"> <?= $i;?></a></li>
<?
} } ?>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>

<? require '../page/layout/footer.php'; }?>