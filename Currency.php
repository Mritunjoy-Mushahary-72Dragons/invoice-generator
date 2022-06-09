<?php
header("Content-type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
/**
 *
 */
//include "db.php";
class Invoice
{

	public static function UPDATE_Currency() {
		$getstart_time = db::$con->query("SELECT start_time FROM currency LIMIT 1");
		$start_time = $getstart_time->fetch_assoc();
		$session_start_time = $start_time['start_time'];
		$sessiom_time = $session_start_time+10800;
    	if($sessiom_time <= time()){
    		$req_url = 'https://api.exchangerate-api.com/v4/latest/USD';
			$response_json = file_get_contents($req_url);
			$response_object = json_decode($response_json, true);
			$array  = array (
			     'USD' => $response_object['rates']['USD'],
			     'CNY' => $response_object['rates']['CNY'],
			     'INR' => $response_object['rates']['INR'],
			     'HKD' => $response_object['rates']['HKD']
			);
			$time = time();
			$sql = '';
			foreach  ( $array  as  $Currency  => $rates ) {
			     $sql .= "UPDATE `currency` SET `rates`=$rates,`start_time`='$time' WHERE (`Currency`='$Currency');";
			}
			if (mysqli_multi_query(db::$con, $sql)) {
			    return true;
			} else {
			    return false;
			}
    	}
	}
	public static function Currency($number,$currency,$convert) {
 		Invoice::UPDATE_Currency();
		$list = [];
		$getCurrency = db::$con->query("SELECT * FROM currency");
		while ($Currency = $getCurrency->fetch_assoc()) {
			 $list[] = $Currency;
        }
//print_r($list);
        foreach ($list as $value) {
        	if($value['Currency'] == 'USD'){
        		$USD = $value['rates'];
        	}
        	if($value['Currency'] == 'CNY'){
        		$CNY = $value['rates'];
        	}
        	if($value['Currency'] == 'INR'){
        		$INR = $value['rates'];
        	}
        	if($value['Currency'] == 'HKD'){
        		$HKD = $value['rates'];
        	}
        }
		if($currency == 'USD'){
			if($convert == 'CNY'){
				return $CNY*$number;
			}elseif($convert == 'INR'){
				return $INR*$number;
			}elseif($convert == 'HKD'){
				return $HKD*$number;
			}elseif($convert == 'USD'){
				return $number;
			}
		}
		if($currency == 'CNY'){
			if($convert == 'USD'){
				return ($USD/$CNY)*$number;
			}elseif($convert == 'INR'){
				return ($INR/$CNY)*$number;
			}elseif($convert == 'HKD'){
				return ($HKD/$CNY)*$number;
			}elseif($convert == 'CNY'){
				return $number;
			}
		}
		if($currency == 'INR'){
			if($convert == 'USD'){
				return ($USD/$INR)*$number;
			}elseif($convert == 'CNY'){
				return ($CNY/$INR)*$number;
			}elseif($convert == 'HKD'){
				return ($HKD/$INR)*$number;
			}elseif($convert == 'INR'){
				return $number;
			}
		}
		if($currency == 'HKD'){
			if($convert == 'USD'){
				return ($USD/$HKD)*$number;
			}elseif($convert == 'CNY'){
				return ($CNY/$HKD)*$number;
			}elseif($convert == 'INR'){
				return ($INR/$HKD)*$number;
			}elseif($convert == 'INR'){
				return $number;
			}
		}
	}
}

 //$_GET['number']='200';
 //$_GET['currency']="HKD";
//	 $_GET['convert']="INR";
//Invoice::UPDATE_currency();
 //echo round(json_encode(Invoice::Currency($_GET['number'],$_GET['currency'],$_GET['convert'])),2);
?>
