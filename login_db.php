<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
session_start();
include "db.php";
include "db2.php";

class login
{

public static function random_code($length = 30) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}~`+=,.;:/?|';
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }


public function insert_details($chkstat,$name,$username,$password,$func_mgr,$postn_mgr,$location,$contact,$level,$files)
	{
					global $con;
					$query=db::$con->query("SELECT * FROM 72daccounts where username ='".$username."'");
					$result=$query->num_rows;
					if($result>=1)
					{
					return  "This email is already registered";
					}
					else
					{
					$name2=str_replace(" ","_",$files['name']);
					$name3=date("Y_m_d_h_i_s",time())."_".$name2;
					$tmpname=$files['tmp_name'];
					$path="images/".$name3;
					move_uploaded_file($tmpname, $path);

					//name/////////////////////////////////////////////////////////////////////////////////////////////

						$name_arr=explode(" ",$name);
					if(count($name_arr)==1)
					{
					$firstname=$name_arr[0];
					$middlename="NULL";
					$lastname="NULL";
					}
					elseif(count($name_arr)==2)
					{
					$firstname=$name_arr[0];
					$middlename="NULL";
					$lastname=$name_arr[1];
					}
					elseif(count($name_arr)==3)
					{
					$firstname=$name_arr[0];
					$middlename=$name_arr[1];
					$lastname=$name_arr[2];
					}
					elseif (count($name_arr)>3) {
					$firstname=$name_arr[0];
					$middlename=$name_arr[1];
					foreach ($name_arr as $key => $value) {
						if($key==0 || $key==1)
						{

						}
						else
						{
							$arr[]=$value;
						}
					}

					$lastname=implode(" ",$arr);
					}

					if($chkstat=="check")
					{	
					$hash_pwd=password_hash($password, PASSWORD_DEFAULT);
					$token=login::random_code();
					$query="INSERT INTO 72daccounts(name,image,location,username,password,token)VALUES(?,?,?,?,?,?)";
					//	$query="INSERT INTO login(name,image,location,username,password)VALUES(?,?,?,?,?)";
					$result=$con->prepare($query);
					$result->bind_param('ssssss',$name,$path,$location,$username,$hash_pwd,$token);
					if($result->execute())
					{


					$id=$result->insert_id;
					$query3=db::$con->query("INSERT INTO staffinfo(memberID,clearanceLevel,firstName,middleName,lastName,email,phoneNumber,avatar,location,functionalManager,locationManager)VALUES('".$id."','".$level."','".$firstname."','".$middlename."','".$lastname."','".$username."','".$contact."','".$path."','".$location."','".$func_mgr."','".$postn_mgr."')");

					$login=new login();
					$login->bal_count($id);

					//echo "userid=".$id;
					$result->close();
					$query="INSERT INTO functional_mgr(name_F,login_id)VALUES(?,?)";
					$result=$con->prepare($query);
					$result->bind_param('si',$name,$id);
					if($result->execute())
					{
					//	echo "new functional inserted";
					}
					$result->close();
					$query="INSERT INTO positional_mgr(name_P,login_id)VALUES(?,?)";
					$result=$con->prepare($query);
					$result->bind_param('si',$name,$id);
					if($result->execute())
					{
					//echo "new positional inserted";
					}
					$result->close();

					$query="INSERT INTO user_funcmgr(funcMgr_id,user_id)VALUES(?,?)";
					$result=$con->prepare($query);
					$result->bind_param('ii',$func_mgr,$id);
					if($result->execute())
					{
					//	echo "functional inserted";
					}
					$result->close();
					$query="INSERT INTO user_posimgr(posiMgr_id,user_id)VALUES(?,?)";
					$result=$con->prepare($query);
					$result->bind_param('ii',$postn_mgr,$id);
					if($result->execute())
					{
					return "inserted";
					}

					}		

					}
					elseif($chkstat=="uncheck")
					{
					$hash_pwd=password_hash($password, PASSWORD_DEFAULT);
					$token=login::random_code();
					$query="INSERT INTO 72daccounts(name,image,location,username,password,token)VALUES(?,?,?,?,?,?)";
					//						$query="INSERT INTO login(name,image,location,username,password)VALUES(?,?,?,?,?)";
					$result=$con->prepare($query);
					$result->bind_param('ssssss',$name,$path,$location,$username,$hash_pwd,$token);
					if($result->execute())
					{
					 $id=$result->insert_id;
					 $query3=db::$con->query("INSERT INTO staffinfo(memberID,clearanceLevel,firstName,middleName,lastName,email,phoneNumber,avatar,location,functionalManager,locationManager)VALUES('".$id."','".$level."','".$firstname."','".$middlename."','".$lastname."','".$username."','".$contact."','".$path."','".$location."','".$func_mgr."','".$postn_mgr."')");
						$login=new login();
						$login->bal_count($id);

					$query="INSERT INTO user_funcmgr(funcMgr_id,user_id)VALUES(?,?)";
					$result=$con->prepare($query);
					$result->bind_param('ii',$func_mgr,$id);
					if($result->execute())
					{
					//	echo "functional inserted";
					}
					$result->close();
					$query="INSERT INTO user_posimgr(posiMgr_id,user_id)VALUES(?,?)";
					$result=$con->prepare($query);
					$result->bind_param('ii',$postn_mgr,$id);
					if($result->execute())
					{
					return "inserted";
					}		


					}

					}

					}	

	}


// public static function all_loctn_ex_one($location)
// {
// 	global $con;
// 	$query="SELECT * FROM login";
// 	$result=$con->prepare($query);
// 	$result->execute();
// 	$get=$result->get_result();
// 	while($row=$get->fetch_assoc())
// 	{
// 		$array[]=$row;
// 	}
// 	// echo "<pre>";
// 	// print_r($array);
// 		$a=[];
// 	foreach ($array as $key => $value) {
// 		foreach ($value as $key2 => $value2) {
// 			if($key2=='location')
// 			{
// 				if($value2!=$location)
// 				{
// 					$a[]=$value2;
// 				}
// 			}

// 		}
// 	}
// 	$jsondata=json_encode($a,true);
// 					return $jsondata;

// }

public static function all_loctn_ex_one($location)
{
	global $con;
	$query="SELECT DISTINCT(location) FROM 72daccounts";
	$result=$con->prepare($query);
	$result->execute();
	$get=$result->get_result();
	while($row=$get->fetch_assoc())
	{
		$array[]=$row;
	}
	// echo "<pre>";
	// print_r($array);
		$a=[];
	foreach ($array as $key => $value) {
		foreach ($value as $key2 => $value2) {
			if($key2=='location')
			{
				if($value2!=$location)
				{
					$a[]=$value2;
				}
			}

		}
	}
	$jsondata=json_encode($a,true);
					return $jsondata;

}

public static function image_send($id)
{
	global $con;
	$query="SELECT image FROM 72daccounts WHERE memberID=?";
		$result=$con->prepare($query);
		$result->bind_param('i',$id);
		$result->execute();
		$get=$result->get_result();
		while($row=$get->fetch_assoc())
		{
		$array[]=$row;
		}
	//	print_r($array);
		$jsondata=json_encode($array,true);
		return $jsondata;

}

public static function bal_count($id)
{
	global $con;
	$causaul_leave="50";
	$sick_leave="50";
	$annual_leave="50";
	$unpaid_leave="50";
	$query="INSERT INTO bal_leave(user_id,causaul_leave,sick_leave,annual_leave,unpaid_leave)VALUES(?,?,?,?,?)";
	$result=$con->prepare($query);
	$result->bind_param('iiiii',$id,$causaul_leave,$sick_leave,$annual_leave,$unpaid_leave);
	if($result->execute())
	{
		//echo "inserted";
	}
}

// public function all_user_details($id)
// {
// 	//,positional_mgr.name
// //	
// 	global $con;
// 					$query="SELECT login.*,functional_mgr.name_F,positional_mgr.name_P from login
// 						LEFT JOIN user_funcmgr ON user_funcmgr.user_id=login.id
// 						LEFT JOIN functional_mgr ON functional_mgr.login_id=user_funcmgr.funcMgr_id
						
// 						LEFT JOIN user_posimgr ON user_posimgr.user_id=login.id
// 						LEFT JOIN positional_mgr ON positional_mgr.login_id=user_posimgr.posiMgr_id
// 						 where login.id=?";
// 					$result=$con->prepare($query);
// 					$result->bind_param('i',$id);
// 					$result->execute();
// 					$get=$result->get_result();
// 					while($row=$get->fetch_assoc())
// 					{
// 						$array[]=$row;
// 					}
// //					print_r($array);
// 					$jsondata=json_encode($array,true);
// 					return $jsondata;



// }

public function all_user_details($id)
{
	//,positional_mgr.name
//	
	global $con;
					$query="SELECT 72daccounts.*,functional_mgr.name_F,functional_mgr.login_id as f_login,positional_mgr.name_P,positional_mgr.login_id as p_login from 72daccounts
						LEFT JOIN user_funcmgr ON user_funcmgr.user_id=72daccounts.memberID
						LEFT JOIN functional_mgr ON functional_mgr.login_id=user_funcmgr.funcMgr_id
						
						LEFT JOIN user_posimgr ON user_posimgr.user_id=72daccounts.memberID
						LEFT JOIN positional_mgr ON positional_mgr.login_id=user_posimgr.posiMgr_id
						 where 72daccounts.memberID=?";
					$result=$con->prepare($query);
					$result->bind_param('i',$id);
					$result->execute();
					$get=$result->get_result();
					while($row=$get->fetch_assoc())
					{
						$array[]=$row;
					}
//					print_r($array);
					$jsondata=json_encode($array,true);
					return $jsondata;



}

public static function checkdata($name)
{
	global $con;
		$query="SELECT * FROM functional_mgr WHERE name_F=?";
		$result=$con->prepare($query);
		$result->bind_param('s',$name);
		$result->execute();
		$result->store_result();
		$count=$result->num_rows;
		if($count==1)
		{
			return true;
		}
		else
		{
			return false;
		}
}





// public static function edit_details($chkstat,$name,$username,$func_mgr,$postn_mgr,$user_id,$location,$files)
// 	{
// 		global $con;

// 	//	echo $files;
// 		if($files=="null")
// 		{
// 			$path='NULL';
// 		}
// 		else
// 		{
// 				$name2=str_replace(" ","_",$files['name']);
// 				$name3=date("Y_m_d_h_i_s",time())."_".$name2;
// 				$tmpname=$files['tmp_name'];
// 				$path="images/".$name3;
// 				move_uploaded_file($tmpname, $path);
// 		}


// 		if($chkstat=="check")
// 		{
// 				if($path!='null'){
			
// 				$query="UPDATE login SET name=?,image=?,location=?,username=? where id=?";
// 				$result=$con->prepare($query);
// 				$result->bind_param('ssssi',$name,$path,$location,$username,$user_id);
// 			}
// 			else
// 			{
// 				$query="UPDATE login SET name=?,location=?,username=? where id=?";
// 				$result=$con->prepare($query);
// 				$result->bind_param('sssi',$name,$location,$username,$user_id);	
// 			}	

// 					if($result->execute())
// 					{
// 							$result->close();
// 							$query="SELECT * FROM functional_mgr WHERE name_F=? AND login_id=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('si',$name,$user_id);
// 												$result->execute();
// 												$result->store_result();
// 												$count=$result->num_rows;
// 												if($count==1)
// 												{
// 													//return false;
// 													//echo "Your Changes Have Been Updated";

// 												}
// 												else
// 												{
// 													$result->close();
// 													$query="INSERT INTO functional_mgr(name_F,login_id)VALUES(?,?)";
// 													$result=$con->prepare($query);
// 													$result->bind_param('si',$name,$user_id);
// 													if($result->execute())
// 													{
// 														//echo "new functional inserted";
// 													}
// 												}


// 								$result->close();
// 								$query="SELECT * FROM positional_mgr WHERE name_P=? AND login_id=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('si',$name,$user_id);
// 												$result->execute();
// 												$result->store_result();
// 												$count=$result->num_rows;
// 												if($count==1)
// 												{
// 												//return false;
// //echo "updated";
// 												}
// 												else
// 												{
// 													$result->close();
// 													$query="INSERT INTO positional_mgr(name_P,login_id)VALUES(?,?)";
// 													$result=$con->prepare($query);
// 													$result->bind_param('si',$name,$user_id);
// 													if($result->execute())
// 													{
// //														echo "new positional inserted";
// 													}
// 												}

// 							$result->close();

// 							$query="SELECT id from login where name=?";
// 							$result=$con->prepare($query);
// 							$result->bind_param('s',$func_mgr);
// 							$result->execute();
// 							$result->bind_result($id_functn);
// 							$result->fetch();
// //							echo "functn_id= ".$id_functn;

// 							$result->close();
// 							$query="SELECT id from login where name=?";
// 							$result=$con->prepare($query);
// 							$result->bind_param('s',$postn_mgr);
// 							$result->execute();
// 							$result->bind_result($id_postn);
// 							$result->fetch();
// //							echo "positn_id= ".$id_postn;
// 							$result->close();



// 							$query="UPDATE user_funcmgr SET funcMgr_id=? where user_id=?";
// 							$result=$con->prepare($query);
// 							$result->bind_param('ii',$id_functn,$user_id);
// 							if($result->execute())
// 							{
// //								echo " function inserted ";
// 							}
							
// 							$result->close();
// 							$query="UPDATE user_posimgr SET posiMgr_id=? where user_id=?";
// 							$result=$con->prepare($query);
// 							$result->bind_param('ii',$id_postn,$user_id);
// 							if($result->execute())
// 							{
// //								echo " positional inserted ";
// 							}

// 					}		
// echo "Your Changes Have Been Updated";
// 		}
// 		elseif($chkstat=="uncheck")
// 		{
// //			echo $chkstat;
// 							// $query="UPDATE login SET name=?,image=?,username=? where id=?";
// 							// $result=$con->prepare($query);
// 							// $result->bind_param('sssi',$name,$path,$username,$user_id);
// 			if($path!='null'){

// 				$query="UPDATE login SET name=?,image=?,location=?,username=? where id=?";
// 				$result=$con->prepare($query);
// 				$result->bind_param('ssssi',$name,$path,$location,$username,$user_id);
// 			}
// 			else
// 			{
// 				$query="UPDATE login SET name=?,location=?,username=? where id=?";
// 				$result=$con->prepare($query);
// 				$result->bind_param('sssi',$name,$location,$username,$user_id);	
// 			}

// 							if($result->execute())
// 							{
// //								echo "updated";
// 												$result->close();
// 												$query="SELECT * FROM functional_mgr WHERE name_F=? AND login_id=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('si',$name,$user_id);
// 												$result->execute();
// 												$result->store_result();
// 												$count=$result->num_rows;
// 												if($count==1)
// 												{
// 													//echo $count;
// 													$result->close();
// 													$query="DELETE   FROM functional_mgr WHERE name_F=? AND login_id=?";
// 													$result=$con->prepare($query);
// 													$result->bind_param('si',$name,$user_id);
// 													if($result->execute())
// 													{
// 														//echo "functional deleted";
// 													}

// 												}
// 												else
// 												{
// 												//	echo "Your Changes Have Been Updated";
// 													//return false;
// 												}

// 												$result->close();
// 												$query="SELECT * FROM positional_mgr WHERE name_P=? AND login_id=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('si',$name,$user_id);
// 												$result->execute();
// 												$result->store_result();
// 												$count=$result->num_rows;
// 												if($count==1)
// 												{

// 													$result->close();
// 													$query="DELETE   FROM positional_mgr WHERE name_P=? AND login_id=?";
// 													$result=$con->prepare($query);
// 													$result->bind_param('si',$name,$user_id);
// 													if($result->execute())
// 													{
// 														//echo "postional deleted";
// 													}

// 												}
// 												else
// 												{
// 													//echo "no2";
// 													//return false;
// 												}




// 												// $result->close();
// 												// $query="INSERT INTO positional_mgr(name_P,login_id)VALUES(?,?)";
// 												// $result=$con->prepare($query);
// 												// $result->bind_param('si',$name,$user_id);
// 												// if($result->execute())
// 												// {
// 												// echo "new positional inserted";
// 												// }


// 												$result->close();
// 												$query="SELECT id from login where name=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('s',$func_mgr);
// 												$result->execute();
// 												$result->bind_result($id_functn);
// 												$result->fetch();
// 												//echo "functn_id= ".$id_functn;

// 												$result->close();
// 												$query="SELECT id from login where name=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('s',$postn_mgr);
// 												$result->execute();
// 												$result->bind_result($id_postn);
// 												$result->fetch();
// 												//echo "positn_id= ".$id_postn;
// 												$result->close();



// 												$query="UPDATE user_funcmgr SET funcMgr_id=? where user_id=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('ii',$id_functn,$user_id);
// 												if($result->execute())
// 												{
// 												//	echo " function inserted ";
// 												}
												
// 												$result->close();
// 												$query="UPDATE user_posimgr SET posiMgr_id=? where user_id=?";
// 												$result=$con->prepare($query);
// 												$result->bind_param('ii',$id_postn,$user_id);
// 												if($result->execute())
// 												{
// 												//	echo " positional inserted ";
// 												}		


// 							}
// echo "Your Changes Have Been Updated";
// 		}

// 	}


public static function edit_details($chkstat,$name,$username,$func_mgr,$postn_mgr,$user_id,$location,$contact,$level,$files)
	{
		print_r($_POST);
		print_r($_FILES);
	$query=db::$con->query("SELECT * FROM 72daccounts where username ='".$username."' and memberID!=".$user_id);
	$result=$query->num_rows;
					if($result>=1)
					{
					return  "This email is already registered";
					}
					else
					{
					
							global $con;

	//	echo $files;
		if($files=="null")
		{
			$path='NULL';
		}
		else
		{
				$name2=str_replace(" ","_",$files['name']);
				$name3=date("Y_m_d_h_i_s",time())."_".$name2;
				$tmpname=$files['tmp_name'];
				$path="images/".$name3;
				move_uploaded_file($tmpname, $path);
		}
////////////////////NAME/////////////
						$name_arr=explode(" ",$name);
					if(count($name_arr)==1)
					{
					$firstname=$name_arr[0];
					$middlename="NULL";
					$lastname="NULL";
					}
					elseif(count($name_arr)==2)
					{
					$firstname=$name_arr[0];
					$middlename="NULL";
					$lastname=$name_arr[1];
					}
					elseif(count($name_arr)==3)
					{
					$firstname=$name_arr[0];
					$middlename=$name_arr[1];
					$lastname=$name_arr[2];
					}
					elseif (count($name_arr)>3) {
					$firstname=$name_arr[0];
					$middlename=$name_arr[1];
					foreach ($name_arr as $key => $value) {
						if($key==0 || $key==1)
						{

						}
						else
						{
							$arr[]=$value;
						}
					}

					$lastname=implode(" ",$arr);
					}
/////////////////////NMAE/////////////////////////////////////
		if($chkstat=="check")
		{
				if($path!='NULL'){
              db::$con->query("UPDATE 72daccounts SET name='".$name."',image='".$path."',location='".$location."',username='".$username."' WHERE memberID  =".$user_id);
$query3=db::$con->query("UPDATE staffinfo SET clearanceLevel='".$level."',firstName='".$firstname."',middleName='".$middlename."',lastName='".$lastname."',email='".$username."',phoneNumber='".$contact."',avatar='".$path."',location='".$location."',functionalManager='".$func_mgr."',locationManager='".$postn_mgr."' WHERE memberID  ='".$user_id."'");
			
				// $query="UPDATE login SET name=?,image=?,location=?,username=? where id=?";
				// $result=$con->prepare($query);
				// $result->bind_param('ssssi',$name,$path,$location,$username,$user_id);

			}
			else
			{
              db::$con->query("UPDATE 72daccounts SET name='".$name."',location='".$location."',username='".$username."' WHERE memberID  =".$user_id);
			$query3=db::$con->query("UPDATE staffinfo SET clearanceLevel='".$level."',firstName='".$firstname."',middleName='".$middlename."',lastName='".$lastname."',email='".$username."',phoneNumber='".$contact."',location='".$location."',functionalManager='".$func_mgr."',locationManager='".$postn_mgr."' WHERE memberID  ='".$user_id."'");

				// $query="UPDATE login SET name=?,location=?,username=? where id=?";
				// $result=$con->prepare($query);
				// $result->bind_param('sssi',$name,$location,$username,$user_id);	
			}	

					//if($result->execute())
if($query3)
					{
						//	$result->close();
							// $query="SELECT * FROM functional_mgr WHERE name_F=? AND login_id=?";
							// 					$result=$con->prepare($query);
							// 					$result->bind_param('si',$name,$user_id);
							// 					$result->execute();
							// 					$result->store_result();
							// 					$count=$result->num_rows;
												$query="SELECT * FROM functional_mgr WHERE login_id=?";
												$result=$con->prepare($query);
												$result->bind_param('i',$user_id);
												$result->execute();
												$result->store_result();
												$count=$result->num_rows;
												if($count==1)
												{
													//return false;
													//echo "Your Changes Have Been Updated";
													db::$con->query("UPDATE functional_mgr SET name_F='".$name."' WHERE login_id='".$user_id."'");
												}
												else
												{
													$result->close();
													$query="INSERT INTO functional_mgr(name_F,login_id)VALUES(?,?)";
													$result=$con->prepare($query);
													$result->bind_param('si',$name,$user_id);
													if($result->execute())
													{
														//echo "new functional inserted";
													}
												}


								$result->close();
								// $query="SELECT * FROM positional_mgr WHERE name_P=? AND login_id=?";
								// 				$result=$con->prepare($query);
								// 				$result->bind_param('si',$name,$user_id);
								// 				$result->execute();
								// 				$result->store_result();
								// 				$count=$result->num_rows;
								$query="SELECT * FROM positional_mgr WHERE login_id=?";
												$result=$con->prepare($query);
												$result->bind_param('i',$user_id);
												$result->execute();
												$result->store_result();
												$count=$result->num_rows;
												if($count==1)
												{
												//return false;
//echo "updated";
											db::$con->query("UPDATE positional_mgr SET name_P='".$name."' WHERE login_id='".$user_id."'");
												}
												else
												{
													$result->close();
													$query="INSERT INTO positional_mgr(name_P,login_id)VALUES(?,?)";
													$result=$con->prepare($query);
													$result->bind_param('si',$name,$user_id);
													if($result->execute())
													{
//														echo "new positional inserted";
													}
												}

							$result->close();

// 							$query="SELECT id from login where name=?";
// 							$result=$con->prepare($query);
// 							$result->bind_param('s',$func_mgr);
// 							$result->execute();
// 							$result->bind_result($id_functn);
// 							$result->fetch();
// //							echo "functn_id= ".$id_functn;

// 							$result->close();
// 							$query="SELECT id from login where name=?";
// 							$result=$con->prepare($query);
// 							$result->bind_param('s',$postn_mgr);
// 							$result->execute();
// 							$result->bind_result($id_postn);
// 							$result->fetch();
// //							echo "positn_id= ".$id_postn;
// 							$result->close();



							$query="UPDATE user_funcmgr SET funcMgr_id=? where user_id=?";
							$result=$con->prepare($query);
							$result->bind_param('ii',$func_mgr,$user_id);
							if($result->execute())
							{
//								echo " function inserted ";
							}
							
							$result->close();
							$query="UPDATE user_posimgr SET posiMgr_id=? where user_id=?";
							$result=$con->prepare($query);
							$result->bind_param('ii',$postn_mgr,$user_id);
							if($result->execute())
							{
//								echo " positional inserted ";
							}

					}		
echo "Your Changes Have Been Updated";
		}
		elseif($chkstat=="uncheck")
		{
		//	echo "dfdfffd".$path;
//			echo $chkstat;
							// $query="UPDATE login SET name=?,image=?,username=? where id=?";
							// $result=$con->prepare($query);
							// $result->bind_param('sssi',$name,$path,$username,$user_id);
			if($path!='NULL'){
              db::$con->query("UPDATE 72daccounts SET name='".$name."',image='".$path."',location='".$location."',username='".$username."' WHERE memberID  =".$user_id);
$query3=db::$con->query("UPDATE staffinfo SET clearanceLevel='".$level."',firstName='".$firstname."',middleName='".$middlename."',lastName='".$lastname."',email='".$username."',phoneNumber='".$contact."',avatar='".$path."',location='".$location."',functionalManager='".$func_mgr."',locationManager='".$postn_mgr."' WHERE memberID  ='".$user_id."'");
				// $query="UPDATE login SET name=?,image=?,location=?,username=? where id=?";
				// $result=$con->prepare($query);
				// $result->bind_param('ssssi',$name,$path,$location,$username,$user_id);
			}
			else
			{
				// $query="UPDATE login SET name=?,location=?,username=? where id=?";
				// $result=$con->prepare($query);
				// $result->bind_param('sssi',$name,$location,$username,$user_id);	
				 db::$con->query("UPDATE 72daccounts SET name='".$name."',location='".$location."',username='".$username."' WHERE memberID  =".$user_id);
			$query3=db::$con->query("UPDATE staffinfo SET clearanceLevel='".$level."',firstName='".$firstname."',middleName='".$middlename."',lastName='".$lastname."',email='".$username."',phoneNumber='".$contact."',location='".$location."',functionalManager='".$func_mgr."',locationManager='".$postn_mgr."' WHERE memberID  ='".$user_id."'");

			}

							// if($result->execute())
					if($query3)
								{
//								echo "updated";
											//	$result->close();
												// $query="SELECT * FROM functional_mgr WHERE name_F=? AND login_id=?";
												// $result=$con->prepare($query);
												// $result->bind_param('si',$name,$user_id);
												// $result->execute();
												// $result->store_result();
												// $count=$result->num_rows;
									$query="SELECT * FROM functional_mgr WHERE login_id=?";
												$result=$con->prepare($query);
												$result->bind_param('i',$user_id);
												$result->execute();
												$result->store_result();
												$count=$result->num_rows;
												if($count==1)
												{
													//echo $count;
													$result->close();
													$query="DELETE   FROM functional_mgr WHERE name_F=? AND login_id=?";
													$result=$con->prepare($query);
													$result->bind_param('si',$name,$user_id);
													if($result->execute())
													{
														//echo "functional deleted";
													}

												}
												else
												{
												//	echo "Your Changes Have Been Updated";
													//return false;
												}

												$result->close();
												// $query="SELECT * FROM positional_mgr WHERE name_P=? AND login_id=?";
												// $result=$con->prepare($query);
												// $result->bind_param('si',$name,$user_id);
												// $result->execute();
												// $result->store_result();
												// $count=$result->num_rows;
												$query="SELECT * FROM positional_mgr WHERE login_id=?";
												$result=$con->prepare($query);
												$result->bind_param('i',$user_id);
												$result->execute();
												$result->store_result();
												$count=$result->num_rows;
												if($count==1)
												{

													$result->close();
													$query="DELETE   FROM positional_mgr WHERE name_P=? AND login_id=?";
													$result=$con->prepare($query);
													$result->bind_param('si',$name,$user_id);
													if($result->execute())
													{
														//echo "postional deleted";
													}

												}
												else
												{
													//echo "no2";
													//return false;
												}




												// $result->close();
												// $query="INSERT INTO positional_mgr(name_P,login_id)VALUES(?,?)";
												// $result=$con->prepare($query);
												// $result->bind_param('si',$name,$user_id);
												// if($result->execute())
												// {
												// echo "new positional inserted";
												// }


												// $result->close();
												// $query="SELECT id from login where name=?";
												// $result=$con->prepare($query);
												// $result->bind_param('s',$func_mgr);
												// $result->execute();
												// $result->bind_result($id_functn);
												// $result->fetch();
												// //echo "functn_id= ".$id_functn;

												// $result->close();
												// $query="SELECT id from login where name=?";
												// $result=$con->prepare($query);
												// $result->bind_param('s',$postn_mgr);
												// $result->execute();
												// $result->bind_result($id_postn);
												// $result->fetch();
												// //echo "positn_id= ".$id_postn;
												// $result->close();



												$query="UPDATE user_funcmgr SET funcMgr_id=? where user_id=?";
												$result=$con->prepare($query);
												$result->bind_param('ii',$func_mgr,$user_id);
												if($result->execute())
												{
												//	echo " function inserted ";
												}
												
												$result->close();
												$query="UPDATE user_posimgr SET posiMgr_id=? where user_id=?";
												$result=$con->prepare($query);
												$result->bind_param('ii',$postn_mgr,$user_id);
												if($result->execute())
												{
												//	echo " positional inserted ";
												}		


							}
echo "Your Changes Have Been Updated";
		}
}
	}
// public function login_details($username,$password)
// {
// 		global $con;
// 		$query="SELECT id,password from login where username=?";
// 		$result=$con->prepare($query);
// 		$result->bind_param('s',$username);
// 		$result->execute();
// 		$result->bind_result($id,$pwd);
// 		$result->fetch();
// 		$result->close();
// 		if(!$id)
// 		{
// 			echo "Username Or Password Was Incorrect. Please Try Again.....";
// 			//echo "";
// 		}
// 		else
// 		{
// 			//if(password_verify(base64_encode($password),$pwd))
// 			if(password_verify($password,$pwd))
// 				{

// 					$_SESSION['user_id']=$id;
// 										echo  "yes";		
// 				}
// 			else
// 			{
// 				echo "password in Incorrect";
// 			}
// 			//return true;
// 		}


// }


 public static function login_details($username, $password) {
        // $username = 'cody@72dragons.com';
        // $password = 'mzp123321';
 	global $con;
        $loginDetails = $con->prepare("SELECT memberID,password FROM 72daccounts WHERE username=?");
        $loginDetails->bind_param('s', $username);
        $loginDetails->execute();
        $loginDetails->bind_result($memberID,$valPass);
        $loginDetails->fetch();

        if (!isset($memberID)) {

          $loginDetails->close();
          return 'The Username Or Password Was Incorrect. Please Try Again.';
        }
         else
         {
           if (password_verify(($password), $valPass))
           {

            $loginDetails->close();
            if (password_needs_rehash(base64_encode($valPass), PASSWORD_DEFAULT)) {

                $newHash = password_hash($password, PASSWORD_DEFAULT);
               db2::$con->query("UPDATE 72daccounts SET password ='".$newHash."' WHERE memberID  =".$memberID);
            $_SESSION["user_id"] = $memberID;    
            }
            $getStaffID = $con->query("SELECT staffID,firstName,middleName,lastName FROM  staffinfo WHERE memberID = $memberID");
            $numRows = $getStaffID->num_rows;
            if ($numRows == 0) {
                return '<div class="small-heading input-error-msg">Invalid Permissions.</div>';
            }
            $staffResult = $getStaffID->fetch_assoc();
           // $_SESSION["user_id"] = $staffResult["staffID"];
            // $_SESSION["name"] = $staffResult["firstName"].' '.$staffResult["middleName"].' '.$staffResult["lastName"];
            // $_SESSION['random_code'] = login::random_code();
            return true;
          	 
          } 
          else 
          {
            return false;
          }
        }
    }
public static function admin_func($id)
{
	global $con;
	$query="SELECT COUNT(*) from functional_mgr where login_id=?";
	$result=$con->prepare($query);
	$result->bind_param('i',$id);
	$result->execute();
	$result->bind_result($count);
	$result->fetch();
	if($count=='1')
	{
		return true;
	}
	else
	{
		return false;
	}

}

public static function all_func()
{
	global $con;
	$query="SELECT * from functional_mgr";
	$result=$con->prepare($query);
	$result->execute();
	$get=$result->get_result();
	while($row=$get->fetch_assoc())
	{
		$array[]=$row;
	}
					$jsondata=json_encode($array,true);
					return $jsondata;

}

public static function all_func_ex_one($name,$username)
{
	global $con;
	$query="SELECT * from functional_mgr where NOT name_F=? and NOT name_F=?";
	$result=$con->prepare($query);
	$result->bind_param('ss',$name,$username);
	$result->execute();
	$get=$result->get_result();
	while($row=$get->fetch_assoc())
	{
		$array[]=$row;
	}
					$jsondata=json_encode($array,true);
					return $jsondata;

}

// public static function all_postn_ex_one($name)
// {
// 	global $con;
// 	$query="SELECT * from positional_mgr where NOT name_P=?";
// 	$result=$con->prepare($query);
// 	$result->bind_param('s',$name);
// 	$result->execute();
// 	$get=$result->get_result();
// 	while($row=$get->fetch_assoc())
// 	{
// 		$array[]=$row;
// 	}
// 					$jsondata=json_encode($array,true);
// 					return   $jsondata;

// }

public static function all_postn_ex_one($name,$username)
{
	global $con;
	$query="SELECT * from positional_mgr where NOT name_P=? and NOT name_P=?";
	$result=$con->prepare($query);
	$result->bind_param('ss',$name,$username);
	$result->execute();
	$get=$result->get_result();
	while($row=$get->fetch_assoc())
	{
		$array[]=$row;
	}
					$jsondata=json_encode($array,true);
					return   $jsondata;

}


public static function all_postn()
{
	global $con;
	$query="SELECT * from positional_mgr";
	$result=$con->prepare($query);
	$result->execute();
	$get=$result->get_result();
	while($row=$get->fetch_assoc())
	{
		$array[]=$row;
	}
					$jsondata=json_encode($array,true);
					return  $jsondata;

}


public static function admin_postn($id)
{
	global $con;
	$query="SELECT COUNT(*) from positional_mgr where login_id=?";
	$result=$con->prepare($query);
	$result->bind_param('i',$id);
	$result->execute();
	$result->bind_result($count);
	$result->fetch();
	if($count=='1')
	{
		return true;
	}
	else
	{
		return false;
	}

}



// public static function forgot_pwd($email)
// {
// 		global $con;
// 		$query="SELECT * FROM 72daccounts where username=?";
// 		$result=$con->prepare($query);
// 		$result->bind_param('s',$email);
// 		$result->execute();
// 		$result->store_result();
// 		//		echo $result->num_rows;
// 		if($result->num_rows==1)
// 		{
// 						$mail = new PHPMailer(true);

// 						try {
// 						    //Server settings
// 						    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
// 						    $mail->isSMTP();                                            // Send using SMTP
// 						    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
// 						    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
// 						    $mail->Username   = 'siddhant@72dragons.com';                     // SMTP username
// 						    $mail->Password   = 'Cannes2019';                               // SMTP password
// 						    $mail->SMTPSecure = 'ssl';         // ssl Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
// 						    $mail->Port       = 465;                                    // 465 TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

// 						    //Recipients
// 						    $mail->setFrom('siddhant@72dragons.com', 'Password');
// 						    $mail->addAddress($email);     // Add a recipient
						  
						    
// 						    // Content
// 						    $mail->isHTML(true);                                  // Set email format to HTML
// 						    $mail->Subject = 'Reset password link';
// 						    $mail->Body    ='Password reset link here :http://localhost/Leave-Tracker-App/resetPassword.php?email='.$email;
// 						  //  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

// 						    $mail->send();
// 						    echo 'Message has been sent';
// 						} catch (Exception $e) {
// 						    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// 						}
// 			////////////////////////////////////////////////////////////
			
// 		}
// 		else
// 		{
// 			echo "Email invalid";
// 		}

// }

    public static function forgot_pwd($email)
    {
            $query="SELECT * FROM  72daccounts where username=?";
            $result=db::$con->prepare($query);
            $result->bind_param('s',$email);
            $result->execute();
            $get=$result->get_result();
            while($row=$get->fetch_assoc())
            {
                $array[]=$row;
            }
            // echo "<pre>";
            // print_r($array);
            //      echo $result->num_rows;
            if(!empty($array))
            {

                
                


                            $mail = new PHPMailer(true);

                            try {
                                //Server settings
                                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                                $mail->isSMTP();                                            // Send using SMTP
                                $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                                $mail->Username   = 'siddhant@72dragons.com';                     // SMTP username
                                $mail->Password   = 'Cannes2019';                               // SMTP password
                                $mail->SMTPSecure = 'ssl';         // ssl Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                                $mail->Port       = 465;                                    // 465 TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                                //Recipients
                                $mail->setFrom('siddhant@72dragons.com', 'Password');
                                $mail->addAddress($email);     // Add a recipient
                              
                                
                                // Content
                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = 'Reset password link';
                                $mail->Body    ='Password reset link here :http://45.76.160.28:4500/resetPassword.php?token='.$array[0]['token'];
                              //  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                                $mail->send();
                                echo 'Message has been sent';
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                ////////////////////////////////////////////////////////////
                
            }
            else
            {
                echo "Email invalid";
            }

    }
public static function mangr_mail($arr)
	{
					//  echo "<pre>";
					// print_r($arr);
						
						
					// $mail->clearAllRecipients();
					// $mail->clearAttachments();
						try {

						    //Server settings
						    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
						    

						    //Recipients
						    foreach ($arr as $key => $value)
						     {
						    $mail = new PHPMailer(true);
						    $mail->isSMTP();                                            // Send using SMTP
						    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
						    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
						 	$mail->Username   = 'siddhant@72dragons.com';                     // SMTP username
						    $mail->Password   = 'Cannes2019';                               // SMTP password
						    $mail->SMTPSecure = 'ssl';         // ssl Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
						    $mail->Port       = 465;                                    // 465 TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
$F_date=date_create($value['fromDate']);
$from_date=date_format($F_date,"d F Y");

$T_date=date_create($value['toDate']);
$to_date=date_format($T_date,"d F Y");

						    $mail->setFrom('siddhant@72dragons.com',$value['user_name']);
						    $mail->addAddress($value['admin']);     // Add a recipient
						  
						    // Content
						    $mail->isHTML(true);                                  // Set email format to HTML
						    $mail->Subject = $value['user_name'].' Has Applied For A Leave';
						    $mail->Body='<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Tracker | Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"
        integrity="sha256-PF6MatZtiJ8/c9O9HQ8uSUXr++R9KBYu4gbNG5511WE=" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body style="background: #ad9440; ">
    <div class="mail_body_container" style="border: 1px solid #000000; margin: 2em;">
        <div class="mail_body_header" style="text-align: center; padding: 2em; border-bottom: 1px solid #000000;">
            <h1>NEW LEAVE REQUEST</h1>
            <p style="margin-top: 1em;">'.$value['user_name'].' as requested '.$value['type'].' for the following dates : '.$from_date.	' to '.$to_date.'</p>
        </div>
        <div class="mail_body_buttons" style="text-align: center; padding: 2em; border-bottom: 1px solid #000000;">
            <p>To approve leave click here:</p>
            <p>Green Box To Approve And Red Box To Deny</p>
            <div class="button_container" style="margin: 1em;">
  <a href="http://45.76.160.28:4500/thanks.php?login_id='.$value['login_id'].'&apply_id='.$value['apply_id'].'&type='.str_replace(" ","_",$value['type']).'&count='.$value['count'].'&user_id='.$value['user_id'].'">
	            <button class="mail_body_approve" style="width: 5em;border: none;outline: none;padding:.3em 1.5em;cursor: pointer;margin-right: .5em;background: green;">
				            <span style="font-size:20px;">&#10003;</span>
				            </button></a>
  <a href="http://45.76.160.28:4500/deny_thanks.php?login_id='.$value['login_id'].'&apply_id='.$value['apply_id'].'&type='.str_replace(" ","_",$value['type']).'&count='.$value['count'].'&user_id='.$value['user_id'].'">
				                <button class="mail_body_deny" style="width: 5em;border: none;padding:.3em 1.5em;outline: none;cursor: pointer; background:#96031a;">
				            		<span style="font-size:22px;">&#215;</span>
				            </button>
            </a>



            </div>
        </div>
        <div class="mail_body_link" style="text-align: center;padding: 5em;">
        	<p>click here to view the request in the leave tracker</p>
            <a href="http://45.76.160.28:4500/login.php" style="text-decoration: none;color: #800000;font-size: 2em;font-weight: bold;">OPEN IN LEAVE TRACKER APP</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>

    </script>
</body>
</html>';
// $mail->Body='<!DOCTYPE html>
// <html>
// <head>
// 	<title></title>
// </head>
// <body>
// <!-- Latest compiled and minified CSS -->
// <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

// <!-- jQuery library -->
// <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

// <!-- Popper JS -->
// <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

// <!-- Latest compiled JavaScript -->
// <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
// <div class="container" border="1px">
//   <h1>New Leave Application</h1>
//   <p>'.$value['user_name'].' as requested '.$value['type'].' for the following dates:'.$from_date.' to '.$to_date.'</p>
// </div>
// <div class="container">
//   <h1>To approve leave click here : </h1>
//   <p><a href="http://localhost/siddhant/72dragons/final/leave/30Dec/Leave_Tracker/thanks.php?login_id='.$value['login_id'].'&apply_id='.$value['apply_id'].'&type='.str_replace(" ","_",$value['type']).'&count='.$value['count'].'&user_id='.$value['user_id'].'">Approve</a>here <a href="http://localhost/siddhant/72dragons/final/leave/30Dec/Leave_Tracker/deny_thanks.php?login_id='.$value['login_id'].'&apply_id='.$value['apply_id'].'&type='.str_replace(" ","_",$value['type']).'&count='.$value['count'].'&user_id='.$value['user_id'].'">deny</a></p>
// </div>
// <div class="container">
//   <h1><a href="http://localhost/siddhant/72dragons/final/leave/30Dec/Leave_Tracker/login.php">OPEN IN LEAVE TRACKER APP</a></h1>
 
// </div>
// </body>
// </html>';
						    $mail->send();
						}
						    // $mail->send();
					}
						 catch (Exception $e) {
					    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
							//return false;
						}
			
	
}

public static function mangr_mail2($arr)
	{
	
					$mail = new PHPMailer(true);
					// $mail->clearAllRecipients();
					// $mail->clearAttachments();
						try {

						    //Server settings
						    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
						    $mail->isSMTP();                                            // Send using SMTP
						    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
						    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
						 	$mail->Username   = 'siddhant@72dragons.com';                     // SMTP username
						    $mail->Password   = 'Cannes2019';                               // SMTP password
						    $mail->SMTPSecure = 'ssl';         // ssl Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
						    $mail->Port       = 465;                                    // 465 TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

						    //Recipients
						    		foreach ($arr as $key => $value) {
						   	$mail->setFrom('siddhant@72dragons.com',$value['admin']);
						    $mail->addAddress($value['user']);     // Add a recipient
						  
						    // Content
						    $mail->isHTML(true);                                  // Set email format to HTML
						    $mail->Subject = 'Leave '.$value['status'];
						    $mail->Body   ="Your leave has been ".$value['status'];
						    //return false;
						    //echo 'Message has been sent';
	
						}
						    $mail->send();
					}
						 catch (Exception $e) {
						//    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
							//return false;
						}
			
	
}

	
public static function reset_password($token,$password,$re_password)
	{
			if($password==$re_password)
			{
				$hash_pwd=password_hash($password, PASSWORD_DEFAULT);
			//	echo $hash_pwd;
				global $con;
				$query="UPDATE 72daccounts SET password=? WHERE token=?";
				$result=$con->prepare($query);
				$result->bind_param('ss',$hash_pwd,$token);
				if($result->execute())
				{
					// $Message = urlencode("password has been updated");
					// header("location:h.php?Message=".$Message);
					// die;
					echo "yes";
				}

			}
			else
			{
					echo "no";
			}
	}


public static function LogOut($id)
	 {
		global $con;
       session_unset();
       session_destroy();
	   echo "login.php";
    }


	/////////////////////////////////////////////////





}

$login=new login();
 // $_GET['api']='login_details';
 // $_POST['email']='siddhantsawant29@gmail.com';
 // $_POST['password']='2';
if(!empty($_GET['api']))
					{
						if($_GET['api']=='login_details')
						{
							echo $login->login_details($_POST['email'],$_POST['password']);
						}
						elseif($_GET['api']=='LogOut')
						{
								 $login->LogOut($_POST['id']);
						}
						elseif($_GET['api']=='admin_func')
						{
								 $login->admin_func($id);
						}
						elseif($_GET['api']=='admin_postn')
						{
								 $login->admin_postn($id);
						}
						elseif($_GET['api']=='forgot_pwd')
						{
								 $login->forgot_pwd($_POST['email']);
						}
						elseif($_GET['api']=='reset_password')
						{
								 $login->reset_password($_POST['token'],$_POST['pass1'],$_POST['pass2']);
						}
						elseif($_GET['api']=='all_user_details')
						{
								 $login->all_user_details($_POST['id']);
						}
						elseif($_GET['api']=='insert_mgr')
						{
								 $login->insert_mgr($_POST);
						}
						elseif($_GET['api']=='insert_details')
						{
		echo $login->insert_details($_POST['check_status'],$_POST['name'],$_POST['username'],$_POST['password'],$_POST['func_mgr'],$_POST['postn_mgr'],$_POST['location'],$_POST['contact'],$_POST['level'],$_FILES['image']);
						}
						elseif($_GET['api']=='all_func')
						{
								 $login->all_func();
						}
						elseif($_GET['api']=='all_postn')
						{
								 $login->all_postn();
						}
						elseif($_GET['api']=='all_func_ex_one')
						{
								 $login->all_func_ex_one($_POST['name']);
						}
						elseif($_GET['api']=='all_postn_ex_one')
						{
								 $login->all_postn_ex_one($_POST['name']);
						}
						elseif($_GET['api']=='edit_details')
						{
							if(empty($_FILES['image']))
							{
	echo 	 $login->edit_details($_POST['check_status'],$_POST['name'],$_POST['username'],$_POST['func_mgr'],$_POST['postn_mgr'],$_POST['login_id'],$_POST['location'],$_POST['contact'],$_POST['level'],$_FILES['image']='null');						
							}
							else
							{
	echo 	$login->edit_details($_POST['check_status'],$_POST['name'],$_POST['username'],$_POST['func_mgr'],$_POST['postn_mgr'],$_POST['login_id'],$_POST['location'],$_POST['contact'],$_POST['level'],$_FILES['image']);												
							}
						}
						elseif($_GET['api']=='mangr_mail')
						{
								 $login->mangr_mail($array);
						}
						elseif($_GET['api']=='mangr_mail2')
						{
								 $login->mangr_mail2($array);
						}

						elseif($_GET['api']=='checkdata')
						{
								 $login->checkdata($name);
						}
						elseif($_GET['api']=='bal_count')
						{
								 $login->bal_count($id);
						}
						elseif($_GET['api']=='image_send')
						{
								 $login->image_send($id);
						}
						elseif($_GET['api']=='all_loctn_ex_one')
						{
								 $login->all_loctn_ex_one($location);
						}

						

}

 ?>
