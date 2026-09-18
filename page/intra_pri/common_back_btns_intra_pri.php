


<script>
$(document).ready(function(){
	//Dashboard button
    $( ".commonBtns .db" )
      .button({
      	icons: {
        primary: "ui-icon-copy"
      }
      })
      .click(function( event ) {
        //event.preventDefault();
      });
      
      //Logout button
      $( ".commonBtns .lo" )
      .button({
      	icons: {
        primary: "ui-icon-power"
      }
      })
      .click(function( event ) {
        //event.preventDefault();
      });
      
      //Back button
      $( ".commonBtns .bk" )
      .button({
      	icons: {
        primary: "ui-icon-arrowreturnthick-1-w"
      }
      })
      .click(function( event ) {
        //event.preventDefault();
      });
  });
</script>

<style>
	.commonBtns{
		padding-right: 68px;
	}
	.commonBtns a{
		float: right;
	}
	.cb{
		clear: both;
	}
</style>
<?php
	function pattern_match_bttn_url($value){					//webmaster(only [](). allowed)
			if(preg_match("/[():\";'<>]/i", $value)){
				return FALSE;
			}else{
				return TRUE;
			}
	}
	
	if(pattern_match_bttn_url($_SERVER['REQUEST_URI'])==FALSE){
		header('Location:'.$config['base_url'].'page/errordoc.php');
	}
?>
    <div class="commonBtns col-sm-offset-0 col-sm-12" style="padding-right: 25px;">
        <div class="row">
        	<div class="col-sm-1">
            <a class="btn btn-sm btn-primary" style="cursor:pointer;" onclick="back_click()" role="button" aria-disabled="false"><i class="fa fa-arrow-circle-left"></i> Back</a>
            </div>

            <div class="col-sm-10" style="padding-right: 1px;">
            
            <a class="btn btn-sm btn-primary" href="<?=$config['base_url'] ?>page/intra_pri/dashboard_intra_pri.php" role="button" aria-disabled="false"><i class="fa fa-tachometer"></i> Dashboard</a>
            </div>
            <div class="col-sm-1">
            
            <a class="btn btn-sm btn-primary" role="button" aria-disabled="false" href="" data-bs-toggle="modal" data-bs-target="#logout"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
            
             </div>
        <script>
        function back_click(){
            window.history.back() ;
        }
        </script>
        <div class="cb"></div>
    </div>
    
    <div class="cb"></div>





<div class="modal fade logout-modal-sm" id="logout" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirm Logout</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p><strong>Are You Sure You Want To Logout ?</strong></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-success btn-sm" href="<?=$config['base_url'] ?>page/intra_pri/logout_intra_pri.php">YES</a> 
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">NO</button>      
      </div>
    </div>
  </div>
</div>