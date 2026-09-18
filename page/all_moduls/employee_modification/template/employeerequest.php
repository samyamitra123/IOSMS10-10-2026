<div class="emplist" style="width:98%; margin: 0 auto;">

    <div class="school" >
		<div class="table-responsive">
            <table width="100%" id="tbl1">
                <tr id="base_header" style="background-color: rgb(221, 247, 255); ">
                <!--<th>NAME OF GP</th>-->
                <th>Employee Name</th>
                <th>Employee ID</th>
                <th>Designation</th>
                <th>Current Basic</th>
                <th>Change Basic</th>
                <th>Status</th>
                <th>Action</th>
                </tr>
                <?php foreach($data as $item):
                  $StatusText = ($item['status']=='1')?'Approved':'Processing';
                  $StatusText = ($item['status']=='2')?'Rejected':$StatusText;
                 ?>
                <tr>
                    <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
                    <td><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
                    <td><?= $item['emp_desig']=='0'?'':$item['desig_name']?></td>
                    <td><?= $item['cur_basic']=='0'?'':$item['cur_basic']?></td>
                    <td><?= $item['basic']=='0'?'':$item['basic']?></td>
                    <td><?= $StatusText; ?></td>
                    <td>
                        <a href="" data-employeeid="<?= $cryptoGraph->encode($item['ech_id'],4);?>" data-bs-toggle="modal" data-bs-target="#viewchange" class="showemployee" data-func="viewemployee">
<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
                    </td>
                </tr>
                <?php endforeach; ?>
           </table>
        </div>
    </div>            
</div>

<div class="modal fade bs-example-modal-lg" id="viewchange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 100%; margin-left: -2.5%;">
      <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Employee Basic Change</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 employeeInfo">
            </div>  
        </div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.showemployee').click(function(){
           var employeeId = $(this).data('employeeid');
           console.log($(this).data('employeeid'));
           console.log($(this).data('func'));
            $.post(
                'ajax_empDetails.php',
                 {empId:employeeId,action:$(this).data('func')} 
                , function(data){
                // alert(data);
                 $('.employeeInfo').html(data);
               });              
        });

    });    
</script>