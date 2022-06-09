<?php 
require "vendor/autoload.php";
include  "api.php";
$obj=new sheet();
$data="";
$data='<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/table.css">
	<title></title>
		<style>
		.main_first{
    		border: none;
    	}
    	.second_main{
    		border: none;
    	}
    	.footer{
            text-align: left; 
            margin:74px 0px 33px 65px;
        }
        .footer1{
        	text-align: left; 
            margin:96px 0px 33px 38px;
        }
    	.btn-sm{
    	display:none;
    	}	
	</style>
</head>
<body>';
$data.=$obj->table_insert($obj->list_invoice($_GET));
$data.='</body>
</html>';
// echo($data);
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($data);
$file=time().".pdf";
$mpdf->Output($file,'D');
header("location:bill_detail.php");
?>
