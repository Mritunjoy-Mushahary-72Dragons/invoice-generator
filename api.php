<?php 
include "db.php";
include "Currency.php";

class sheet
{
	public static function list_invoice($details)
	{
//		 echo "<pre>";
//		 print_r($details);

	

		if(!empty($details['invoice_id']))
		{

											$result=db::$con->query("SELECT
												 Invoice_list.invoice_id,
												 Invoice_list.Username,
												 Invoice_list.type_of_payment,
												 Sign.sign_id,
												 Sign.sign_long,
												 Username.username,
												 Invoice_list.billed_from as billed_from_id,
												 Invoice_list.dates,
												 currency.Currency_id,
												 currency.Country,
												 currency.Currency AS Currency_to,
												 Address.address as billed_to 
												 FROM Invoice_list
											LEFT JOIN Address ON Address.addr_id = Invoice_list.billed_to
											LEFT JOIN  currency ON  currency.Currency_id = Invoice_list.Country
											LEFT JOIN  Username ON  Username.user_id = Invoice_list.Username
											LEFT JOIN Sign ON Sign.sign_id = Invoice_list.type_of_payment		
											WHERE Invoice_list.invoice_id='".$details['invoice_id']."' AND  Invoice_list.removed_on IS NULL");
		}
		else
		{
								if(!empty($details['Username']))
											{
											$result=db::$con->query("SELECT Invoice_list.invoice_id,Invoice_list.billed_from,Invoice_list.dates,currency.Country,Invoice_list.billed_from as billed_from_id,currency.Currency AS Currency_to,Address.address as billed_to FROM Invoice_list
											LEFT JOIN Address ON Address.addr_id = Invoice_list.billed_to
											LEFT JOIN  currency ON  currency.Currency_id = Invoice_list.Country
											WHERE Invoice_list.Country='".$details['country']."' AND  
												Invoice_list.Username='".$details['Username']."' AND 
												Invoice_list.type_of_payment ='".$details['type_of_payment']."' AND
											Invoice_list.dates ='".$details['dates']."' AND  Invoice_list.removed_on IS NULL");
											}
											else
											{
												$result=db::$con->query("SELECT Invoice_list.invoice_id,Invoice_list.billed_from,Invoice_list.dates,currency.Country,Invoice_list.billed_from as billed_from_id,currency.Currency AS Currency_to,Address.address as billed_to
											FROM Invoice_list
											LEFT JOIN Address ON Address.addr_id = Invoice_list.billed_to
											LEFT JOIN  currency ON  currency.Currency_id = Invoice_list.Country
											WHERE Invoice_list.Country='".$details['country']."' AND  
												Invoice_list.Username IS NULL AND 
												Invoice_list.type_of_payment ='".$details['type_of_payment']."' AND
											Invoice_list.dates ='".$details['dates']."' AND  Invoice_list.removed_on IS NULL");	
											}		
		}

		   
			while($row=$result->fetch_assoc())
			{


				 $result2=db::$con->query("SELECT address FROM Address WHERE addr_id='".$row['billed_from_id']."'");
 				 $row['billed_from']=$result2->fetch_assoc()['address'];

				$result4=db::$con->query("SELECT invoice_no.invoice_no_id,Sign.sign_short 
				FROM invoice_no
				LEFT JOIN Sign ON Sign.sign_id = invoice_no.sign		
				WHERE invoice_no.invoice_id='".$row['invoice_id']."'");
				$row4=$result4->fetch_assoc();
				$row['invoice']=$row4;


				$result3=db::$con->query("SELECT Refer_no.refer_no_id ,Sign.sign_short 
				FROM Refer_no
				LEFT JOIN Sign ON Sign.sign_id = Refer_no.sign		
				WHERE Refer_no.invoice_id='".$row['invoice_id']."'");
				$row3=$result3->fetch_assoc();
				$row['refer_no']=$row3;

				  $result5=db::$con->query("SELECT Description.description_id,Description.description as des_title,
				  								Description.amount
				  						 FROM Description 
				  						 WHERE Description.invoice_id='".$row['invoice_id']."' AND Description.sub_description IS NULL AND  Description.invoice_id IS NOT NULL");
				$row2=$result5->fetch_assoc();
				 $arr=$row2;
				 	//$total=0;
				 $arr2=[];
												  $result6=db::$con->query("SELECT Description.description_id,Description.description as sub_title,
				  								Description.amount				  												  								
				  						 FROM Description 
				  						 WHERE Description.sub_description='".$row2['description_id']."'");
									  	while($row5=$result6->fetch_assoc())
									  	{
									  		$_GET['currency_from']='HKD';

						  					$row5['currency_conversion']=round(json_encode(Invoice::Currency($row5['amount'],$_GET['currency_from'],$row['Currency_to'])),2);
									  
									  		$arr2[]=$row5;
											}
									  	if(empty($arr2))
									  	{
									  	$arr['sub_description']='';
									  	}
									  	else
									  	{
									  	$arr['sub_description']=$arr2;									  		
									  	}

//print_r($row);
 $_GET['currency_from']='HKD';
 $arr['currency_conversion']= round(json_encode(Invoice::Currency($arr['amount'],$_GET['currency_from'],$row['Currency_to'])),2);
///print_r($arr);
		$row['description']=$arr;
				$array[]=$row;
			}
		 
//		  echo "<pre>";
//		 print_r($array);

if(!empty($details['invoice_id']))
{
//echo "d";
return  $array;
}
else
{
$obj=new sheet();
return  $obj->table_insert($array);
}

	}

public static function table_insert($arr)
{
$data='';
$data.='<div class="main_first"><div class="header"><h3>THE DRAGON YEAR LIMITED, LLC <img src="logo.PNG" width="62" height="62" style="vertical-align:middle;margin: 14px 6px"></h3></div>';
foreach ($arr as $key => $value) {
$data.='<div class="contain"><table  style="margin: auto;"> <tr>
				    <th colspan="4" style="padding: 0px 0px 19px;">Billed To</th>
				  </tr>
				  <tr class="tr_fst">
				    <td colspan="3">'.$value['billed_to'].'</td>
				    <td colspan="1" class="td_fst">Invoice No:'.$value['invoice']['sign_short']."".$value['invoice']['invoice_no_id'].'
				 <br>Refer to '.$value['refer_no']['sign_short']."".$value['refer_no']['refer_no_id'].'
						<br>Date:'.date("F j, Y", strtotime($value['dates'])).'</td>
				 </tr>
				   <tr>
				    <th colspan="4" style="padding: 35px 0px 19px;">Billed From</th>
				  </tr>
				   <tr class="tr_fst">
				    <td colspan="3" style="border-right: 0px solid;">'.$value['billed_from'].'</td>
				    <td style="border-left: 0px solid;"><img src="img/72logo.png" style="width: 100px; float: right;"></td>
				  </tr>
				  <tr>
				  	<td colspan="4" style="padding: 18px;"></td>
				  </tr>
<tr id="sty">
				    <th colspan="2">Description</th>
				    <th colspan="2">Amount</th>
				  </tr>
<tr id="sty">
				    <td colspan="2">'.$value['description']['des_title'].'</td>
				    <td colspan="2" style="border-left:1px solid ;">HKD:'.$value['description']['amount'].'</td>
				  </tr>
				  <tr id="sty">
				    <th colspan="2">Total</th>
				    <th colspan="2">HKD:'.$value['description']['amount'].'</th>
				  </tr>
 		</table>
	
</div><h4 class="footer">Bradley C. Miller<br>CEO of The Dragon Year Limited, LLC</h4></div>
<div class="second_main">
		<div class="header bill2">
 			<h3>THE DRAGON YEAR LIMITED, LLC <img src="logo.PNG" width="62" height="62" style="vertical-align:middle;margin: 14px 6px;"> </h3>
 		</div>
 		<div class="contain"><table  style="margin: auto;"> <tr>
				    <th colspan="4" style="padding: 0px 0px 19px;">Billed To</th>
				  </tr>
				  <tr class="tr_fst">
				    <td colspan="3">'.$value['billed_to'].'</td>
				    <td colspan="1" class="td_fst">Invoice No: '.$value['refer_no']['sign_short']."".$value['refer_no']['refer_no_id'].' 
						<br>Date: '.date("F j, Y", strtotime($value['dates'])).'</td>
				  </tr>
				  <tr>
				    <th colspan="4"  style="padding: 35px 0px 19px;">Billed From</th>
				  </tr>
				 <tr class="tr_fst">
				    <td colspan="3" style="border-right: 0px solid;">'.$value['billed_from'].'</td>
				    <td style="border-left: 0px solid;"><img src="img/72logo.png" style="width: 100px; float: right;padding-right: 0px;"></td>
				  </tr>
				 <tr>
				  	<td colspan="4" style="padding: 18px;"></td>
				  </tr>
				    
				  <tr id="sty" style="border-top: 1px solid; border-bottom: 1px solid;">
				    <th colspan="2">Description</th>
				    <th colspan="2">Amount</th>
				  </tr>';

				   if(!empty($value['description']['sub_description']))
				  {
				  foreach ($value['description']['sub_description'] as $key2 => $value2) {
				  
							$data.='<tr id="sty">
								<td colspan="2">'.$value2['sub_title'].'</td>
								<td colspan="2" style="border-left:1px solid";">RMB:  '.$value2['currency_conversion'].' (HKD: '.$value2['amount'].' )</td>
								</tr>';								
				  }
				}
				else
				{
		$data.='<tr id="sty">
								<td colspan="2">'.$value['description']['des_title'].'</td>
								   <th colspan="2" style="border-left: 1px solid;">RMB: '.$value['description']['currency_conversion'].' (HKD: '.$value['description']['amount'].')</th>
							</tr>';
			}
			 $data.='<tr id="sty">
				    <th colspan="2">Total</th>
				    <th colspan="2">RMB: '.$value['description']['currency_conversion'].' (HKD: '.$value['description']['amount'].')</th>
				  </tr>
	</table>';	
}

$data.='<h4 class="footer1">Bradley C. Miller<br>CEO of The Dragon Year Limited, LLC</h4>
 	</div> </div>
 	<div class="btn-sm"> 
 	  <button><a class="save-user" href="http://45.76.160.28:5000/Invoice_sheet/invoice.php?invoice_id='.$arr[0]['invoice_id'].'" style="text-decoration: none;color: #000000;">Edit</a></button>
 	  	   <button class="save-user" onclick=del('.$value['invoice_id'].'); style="text-decoration: none;color: #000000;">Delete</button>
	 	<button><a href="pdf.php?invoice_id='.$value['invoice_id'].'" style="text-decoration: none;color: #000000;">Download</a></button>
 	</div>';
return $data;
}


	public static function insert_invoice($details)
	{

	//	 print_r($details);

if($details['amot']!="undefined" && $details['amount'][0]!=array_sum($details['amot']))
{
    echo "false";

}
elseif(!empty($details['username']) && !empty($details['type_of_payment']) && $details['username']!='undefined' && $details['type_of_payment']!='undefined')
{
	$getaddress = db::$con->query("SELECT * FROM Invoice_list WHERE Country='".$details['country']."' AND  
												Username='".$details['username']."' AND 
												type_of_payment ='".$details['type_of_payment']."' AND
												dates ='".$details['dates']."'  AND
												billed_from ='".$details['billed_from']."'  
												AND  removed_on IS NULL");
	if($getaddress->num_rows > 0)
	{
		echo "already_user";
	}
	else
	{
		$obj=new sheet();
 $obj->insert_data($details);
	}

}
elseif((empty($details['username']) || $details['username']=='undefined') && (empty($details['type_of_payment']) || $details['type_of_payment']=='undefined'))
{
	// echo "dfd";
	
	$getaddress = db::$con->query("SELECT * FROM Invoice_list WHERE Country='".$details['country']."' AND  
												type_of_payment ='".$details['type_of_payment_cmp']."' AND
												dates ='".$details['dates']."'  AND
												billed_from ='".$details['billed_from']."'  
												AND  removed_on IS NULL");
	if($getaddress->num_rows > 0)
	{
		echo "already_company";
	}
	else
	{
		$obj=new sheet();
$obj->insert_data($details);
	}

}
	}


	public static function insert_data($details)
	{
		$details['main']=array_combine($details['description'],$details['amount']);
				$bill_to='1';
				if(!empty($details['username']) && !empty($details['type_of_payment']) && $details['username']!='undefined' && $details['type_of_payment']!='undefined')
				{
					$result=db::$con->query("INSERT INTO Invoice_list(billed_to,billed_from,Country,Username,type_of_payment,dates)VALUES('".$bill_to."','".$details['billed_from']."','".$details['country']."','".$details['username']."','".$details['type_of_payment']."','".$details['dates']."')");
						echo $id=db::$con->insert_id;
						$result=db::$con->query("INSERT INTO  invoice_no(invoice_id,sign)VALUES('".$id."','".$details['type_of_payment']."')");
						$invoice_no_id=db::$con->insert_id;
						$result=db::$con->query("INSERT INTO  Refer_no(invoice_id,sign)VALUES('".$id."','".$details['type_of_payment']."')");
						$refer_no_id=db::$con->insert_id;
				}	
				else
				{

					$result=db::$con->query("INSERT INTO Invoice_list(billed_to,billed_from,Country,type_of_payment,dates)VALUES('".$bill_to."','".$details['billed_from']."','".$details['country']."','".$details['type_of_payment_cmp']."','".$details['dates']."')");
					echo  $id=db::$con->insert_id;
					$result=db::$con->query("INSERT INTO  invoice_no(invoice_id,sign)VALUES('".$id."','".$details['type_of_payment_cmp']."')");
					$invoice_no_id=db::$con->insert_id;
					$result=db::$con->query("INSERT INTO  Refer_no(invoice_id,sign)VALUES('".$id."','".$details['type_of_payment_cmp']."')");
					$refer_no_id=db::$con->insert_id;
				}			  

				$result=db::$con->query("UPDATE Invoice_list SET invoice_no='".$invoice_no_id."', refer_no='".$refer_no_id."' WHERE invoice_id='".$id."'");


				foreach ($details['main'] as $key => $value) {
				$result=db::$con->query("INSERT INTO Description(description,amount,invoice_id)VALUES('".$key."','".$value."','".$id."')");
				$description_id=db::$con->insert_id;
				}		 		


				if($_POST['desc']!='undefined' &&  $_POST['amot']!='undefined')
				{
				$details['sub']=array_combine($details['desc'],$details['amot']);
				foreach ($details['sub'] as $key2 => $value2)
				{
				$result=db::$con->query("INSERT INTO Description(description,sub_description,amount)VALUES('".$key2."','".$description_id."','".$value2."')");
				}
				}

	}

	public static function edit_invoice($details)
	{
		// echo "<pre>";
		// print_r($details);

if($details['amot']!="undefined" && $details['amount'][0]!=array_sum($details['amot']))
{
    echo "false";

}
else
{
		if(isset($details['type_of_payment']) && ($details['username']) && $details['type_of_payment']!='undefined' && $details['username']!='undefined')
		{
		$result=db::$con->query("UPDATE Invoice_list SET 
		billed_from ='".$details['billed_from']."',
		Country='".$details['country']."', 
		Username ='".$details['username']."',
		type_of_payment  ='".$details['type_of_payment']."'		
		WHERE invoice_id='".$details['invoice_id']."' AND dates  ='".$details['dates']."'");
		$result2=db::$con->query("UPDATE  invoice_no SET sign ='".$details['type_of_payment']."' WHERE invoice_id='".$details['invoice_id']."'");
		$result3=db::$con->query("UPDATE Refer_no SET sign ='".$details['type_of_payment']."' WHERE invoice_id='".$details['invoice_id']."'");
		}
		else
		{
		$result=db::$con->query("UPDATE Invoice_list SET 
		billed_from ='".$details['billed_from']."',
		Country='".$details['country']."', 
		Username=NULL,
		type_of_payment  ='".$details['type_of_payment_cmp']."'		
		WHERE invoice_id='".$details['invoice_id']."' AND dates  ='".$details['dates']."'");	
		$result2=db::$con->query("UPDATE  invoice_no SET sign ='".$details['type_of_payment_cmp']."' WHERE invoice_id='".$details['invoice_id']."'");
		$result3=db::$con->query("UPDATE Refer_no SET sign ='".$details['type_of_payment_cmp']."' WHERE invoice_id='".$details['invoice_id']."'");
		}




		$result4=db::$con->query("DELETE FROM Description WHERE invoice_id='".$details['invoice_id']."'");
		$result4=db::$con->query("DELETE FROM Description WHERE invoice_id='".$details['invoice_id']."'");
		$details['main']=array_combine($details['description'],$details['amount']);

		foreach ($details['main'] as $key => $value) {
		$result=db::$con->query("INSERT INTO Description(description,amount,invoice_id)VALUES('".$key."','".$value."','".$details['invoice_id']."')");
		$description_id=db::$con->insert_id;
		}		 		


		if($_POST['desc']!='undefined' &&  $_POST['amot']!='undefined')
		{
		$details['sub']=array_combine($details['desc'],$details['amot']);
		foreach ($details['sub'] as $key2 => $value2)
		{
		$result=db::$con->query("INSERT INTO Description(description,sub_description,amount)VALUES('".$key2."','".$description_id."','".$value2."')");
		}
		}

}
}




public static function del_invoice($details)
{
print_r($details);
		$result=db::$con->query("UPDATE Invoice_list SET removed_on=CURRENT_TIMESTAMP WHERE invoice_id='".$details['invoice_id']."'");
//header("location:bill_detail.php?msg=Deleted");
if($result)
{
	return true;
}
else
{
	return false;
}
}


public static function cmp_paymt_list($details) {
							
										//	print_r($details);			
								// if(empty($details['type_of_payment']) && !empty($details['country']))
								// {
								// $getaddress = db::$con->query("SELECT Sign.sign_id,Sign.sign_short,Sign.sign_long FROM  Invoice_list
								// LEFT JOIN Sign ON Sign.sign_id = Invoice_list.type_of_payment
								//  WHERE Invoice_list.Country='".$details['country']."' AND Invoice_list.Username IS NULL AND Invoice_list.removed_on IS NULL");
								// $data='';
								// while ($address = $getaddress->fetch_assoc()) {
								// $data.="<option value=".$address['sign_id'].">".$address['sign_long']."</option>";
								// }
								// }
								// elseif(!empty($details['type_of_payment']) && !empty($details['country']))
								// {
								// $getaddress = db::$con->query("SELECT dates FROM  Invoice_list
								//  WHERE Country='".$details['country']."' AND  type_of_payment='".$details['type_of_payment']."' AND Invoice_list.Username IS NULL  and Invoice_list.removed_on IS NULL ");
								// $data='';
								// while ($address = $getaddress->fetch_assoc()) {
								// $data.="<option >".$address['dates']."</option>";
								// }	
								// }
								// return $data;




										//		print_r($details);			
								if(empty($details['type_of_payment']) && !empty($details['country']))
								{
									$getaddress = db::$con->query("SELECT * FROM  Sign
									 WHERE for_company ='".$details['country']."'");
									$data='';
									$data='<option value="0">Select</option>';
									while ($address = $getaddress->fetch_assoc()) {
									$data.="<option value=".$address['sign_id'].">".$address['sign_long']."</option>";
										}
								}
								elseif(!empty($details['type_of_payment']) && !empty($details['country']))
								{
										$getaddress = db::$con->query("SELECT dates FROM  Invoice_list
										 WHERE Country='".$details['country']."' AND  type_of_payment='".$details['type_of_payment']."' AND Invoice_list.Username IS NULL  and Invoice_list.removed_on IS NULL ");
										$data='';
										$data='<option value="0">Select</option>';
										while ($address = $getaddress->fetch_assoc()) {
										$data.="<option >".$address['dates']."</option>";
										}	
								}
								return $data;
	}






public static function paymt_list($details)
{
			// echo "<pre>";
			// 			print_r($details);
			if(empty($details['type_of_payment']) && empty($details['Username']) && !empty($details['country']))
			{
						$getaddress = db::$con->query("SELECT * FROM  Username
										 WHERE country_id='".$details['country']."'");

						$data='';
						$data='<option value="0">Select</option>';
						while ($address = $getaddress->fetch_assoc())
						{
							$data.="<option value=".$address['user_id'].">".$address['username']."</option>";
						}
			}
			elseif(empty($details['type_of_payment']) &&  !empty($details['Username']) && !empty($details['country']))
			{
						$getaddress = db::$con->query("SELECT Sign.sign_id,Sign.sign_short,Sign.sign_long FROM  Invoice_list
									LEFT JOIN Sign ON Sign.sign_id = Invoice_list.type_of_payment
										 WHERE Invoice_list.Country='".$details['country']."' AND Invoice_list.Username='".$details['Username']."'  AND Invoice_list.removed_on IS NULL");
						$data='';
						$data='<option value="0">Select</option>';
						while ($address = $getaddress->fetch_assoc())
						{
							$data.="<option value=".$address['sign_id'].">".$address['sign_long']."</option>";
						}
			}
			elseif(!empty($details['type_of_payment']) &&  !empty($details['Username']) && !empty($details['country']))
			{
						$getaddress = db::$con->query("SELECT dates FROM  Invoice_list
										 WHERE Country='".$details['country']."' AND  type_of_payment='".$details['type_of_payment']."' AND Invoice_list.Username='".$details['Username']."' and Invoice_list.removed_on IS NULL ");	
						$data='';
						$data='<option value="0">Select</option>';
						while ($address = $getaddress->fetch_assoc())
						{
							$data.="<option >".$address['dates']."</option>";
						}
			}
			// echo "<pre>";
			// 	print_r($data);
			return $data;
}


public static function address($details) {
		$list = [];
		if(empty($details['billed_from']))
		{
		$getaddress = db::$con->query("SELECT * FROM Address WHERE addr_id != 1");
		while ($address = $getaddress->fetch_assoc()) {
			$list[] = $address;
		
		}
	}
	else
	{
			$getaddress = db::$con->query("SELECT * FROM Address WHERE addr_id != 1 and addr_id !='".$details['billed_from']."'");
		while ($address = $getaddress->fetch_assoc()) {
			$list[] = $address;
		
		}
	}

		return $list;
	}

public static function country($details) {
		$list = [];
	if(empty($details['Country']))
		{
		$getaddress = db::$con->query("SELECT * FROM currency");
										while ($address = $getaddress->fetch_assoc()) {
										$list[] = $address;
										}
	}
	else
	{
										$getaddress = db::$con->query("SELECT * FROM currency WHERE  Currency_id !='".$details['Country']."'");
										while ($address = $getaddress->fetch_assoc()) {
										$list[] = $address;
										}
		
		}
	
		
		return $list;
	}


public static function crty_list($details) {
	//print_r($details);
	if($details['chk_send']!="false")
	{
					$getaddress = db::$con->query("SELECT * FROM  Sign
					WHERE for_user ='".$details['country']."'");
					$data='';
					$data='<option value="0">Select</option>';
					while ($address = $getaddress->fetch_assoc()) {
					$data.="<option value=".$address['sign_id'].">".$address['sign_long']."</option>";
					}
	}
	else
	{
					$getaddress = db::$con->query("SELECT * FROM  Sign
					WHERE for_company ='".$details['country']."'");
					$data='';
					$data='<option value="0">Select</option>';
					while ($address = $getaddress->fetch_assoc()) {
					$data.="<option value=".$address['sign_id'].">".$address['sign_long']."</option>";
					}
	}

	return $data;
}


	public static function user_list($details) {
		$getaddress = db::$con->query("SELECT * FROM Username WHERE country_id ='".$details['country']."'");
		$data='';
					$data='<option value="0">Select</option>';
					while ($address = $getaddress->fetch_assoc()) {
					$data.="<option value=".$address['user_id'].">".$address['username']."</option>";
					}
		return $data;
	}


	public static function sign_list($details){
		$list = [];

		if(isset($details['for_user']))
		{
$getCurrency = db::$con->query("SELECT * FROM Sign WHERE for_user='".$details['Currency_id']."' AND sign_id !='".$details['sign_id']."'");
		while ($Currency = $getCurrency->fetch_assoc()) {
			$list[] = $Currency;
		}
		}
		
		else
		{
$getCurrency = db::$con->query("SELECT * FROM Sign WHERE for_company='".$details['Currency_id']."' AND sign_id !='".$details['sign_id']."'");
		while ($Currency = $getCurrency->fetch_assoc()) {
			$list[] = $Currency;
		}

		}

		
		return $list;
	}

	public static function update_user_list($id,$country)
	{
				$list = [];

				$getaddress = db::$con->query("SELECT * FROM Username WHERE user_id !='".$id."' AND country_id='".$country."'");
					while ($Currency = $getaddress->fetch_assoc()) {
			$list[] = $Currency;
					}
				return $list;

	}




}


$obj=new sheet();
 //$_GET['api']='list_invoice';
 // $_POST['invoice_id']='3';
// $_POST['billed_from']='1';
// $_POST['billed_to']='1';
 // $_POST['country']='2';
 // // $_POST['Username']='3';
 //   $_POST['type_of_payment']='3';
 //   $_POST['dates']='2021-12-04';



// $_POST['description']=array('d');
// $_POST['amount']=array('2');


// $_POST['desc']=array('e','t');
// $_POST['amot']=array('2','4');




//$_POST['description']=array('des'=>'a','amt'=>'1000','sub'=>array(array('des'=>'b','amt'=>'1000'),array('des'=>'c','amt'=>'1500')));
//$_POST['description']=array('des'=>'a','amt'=>'1000','sub'=>'');

					if(!empty($_GET['api']))
					{
												if($_GET['api']=='list_invoice')
												{
											 	 echo $obj->list_invoice($_POST);
												}
												elseif($_GET['api']=='insert_invoice')
												{
											echo 	$obj->insert_invoice($_POST);
												}
												elseif($_GET['api']=='edit_invoice')
												{
											echo 	$obj->edit_invoice($_POST);
												}
												elseif($_GET['api']=='del_invoice')
												{
											echo	$obj->del_invoice($_POST);
												}
												elseif($_GET['api']=='cmp_paymt_list')
												{
									echo 	$obj->cmp_paymt_list($_POST);
												}
												elseif($_GET['api']=='paymt_list')
												{
									echo 	$obj->paymt_list($_POST);
												}
												elseif($_GET['api']=='crty_list')
												{
									echo 	$obj->crty_list($_POST);
												}
												elseif($_GET['api']=='user_list')
												{
									echo 	$obj->user_list($_POST);
												}
												
				}
				 ?>
