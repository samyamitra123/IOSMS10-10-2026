	
<?php
function check(gpf,pf_loan,i_tax,gsli,reduct)
		{
			if(gpf=='' && gsli=='' && i_tax=='' && pf_loan=='' && reduct=='')
			{
				gpf=0;
				gsli=0; 
				i_tax==0;
				 pf_loan==0;
				  reduct==0;
				}
			else if(gsli=='' && i_tax=='')
			{
			     gsli=0; 
				i_tax==0;
			}
			else if(gpf=='' && gsli=='')
			{
			    gpf=0;
				gsli=0; 
			}
			
			}

?>