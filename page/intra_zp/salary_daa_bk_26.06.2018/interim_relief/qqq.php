
                                                
                                                
												<?php
													if($item['emp_id_pk']==$ir_saved[$i]['emp_id_fk'] && ($ir_saved[$i]['ir_status']=='2' || $ir_saved[$i]['ir_status']=='3'|| $ir_saved[$i]['ir_status']=='4' && ($ir_saved[$i]['unlock_req']=='0'||$ir_saved[$i]['unlock_req']=='1')))
													{  
														$saved = 1; ?>
														<td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" style="opacity:0.5;" alt="Edit"></td>
														<td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Save"></td>
													<?php
                                                    }
                                                
                                                if($saved == 0)
                                                { ?>
                                                    <td>
                                                        <div class="edit_id<?=$cnt?>">
                                                        	<a data-toggle="modal" onClick="submit_ir(<?=$cnt;?>);" style="cursor:pointer; "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" ></a>
                                                        </div>
                                                    </td>
                                                    
                                                    <td class="save" id="save">
                                                        <div class="save_id<?=$cnt?>">
                                                        	<a id="ir_save&<? echo $cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cnt; ?>" onClick="save_ir(this.id);"><img id="img_id<?=$cnt?>" onclick="return hidemsg(this.id)" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="save"></a>
                                                        </div>
                                                    </td>
                                                <?php } 
													if($item['emp_id_pk']==$ir_saved[$i]['emp_id_fk'] && $ir_saved[$i]['ir_status']=='2')
													{ 
														$send_val=1; ?>  
														<td class="send" id="send">
														<div class="sent_id<?=$cnt?>">
															<a id="ir_send&<? echo $cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cnt; ?>" onClick="send_ir(this.id);"><img id="img_id<?=$cnt?>" width="25" src="<?= $config['base_url'] ?>themes/default/image/send.png" alt="send"></a>
														</div>
														</td>
													<?php   	
													}
                                               
                                                if($send_val == 0)
                                                {  ?>
                                                    <td>
                                                        <div class="sent_disable<?=$cnt?>">
                                                        	<img width="25" src="<?= $config['base_url'];?>themes/default/image/send_disable.png" alt="send" />
                                                        </div>
                                                    </td>
                                                
                                                <?php 
												} 
                                                $unlock_req = 0;
                                                for($i =0 ; $i < count($ir_saved) ; $i++)
                                                {
													if($item['emp_id_pk']==$ir_saved[$i]['emp_id_fk'] && ($ir_saved[$i]['ir_status']=='4' && $ir_saved[$i]['unlock_req']=='0'))
													{  
														$unlock_req = 1; ?>
														<td id="unlock_req" class="unlock_req"><a id="<? echo $cryptoGraph->encode($item['emp_id_pk'],4) ?>" onClick="show_unlock(this.id);"> <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color:#EF5350;"></i> </a></td>
													<?php 
													}
                                                }
                                                if($unlock_req == 0)
                                                {  ?>
                                                    <td >
                                                    	<i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color:#F8BBD0;"></i>
                                                    </td>
                                                <?php 
												} ?>