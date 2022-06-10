<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
header("Content-type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
session_start();
include('db.php');
class StaffMember {

    //Log the staff member in by the username, password, and unique token provided. Will redirect to the url provided if the $redirect variable is not null
    public static function loginStaff($username, $password) {
        // $username = 'cody@72dragons.com';
        // $password = 'mzp123321';
        $loginDetails = db::$con->prepare("SELECT memberID,password FROM 72daccounts WHERE username=?");
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
               db::$con->query("UPDATE 72daccounts SET password ='".$newHash."' WHERE memberID  =".$memberID);
              
            }
            $getStaffID = db::$con->query("SELECT staffID,firstName,middleName,lastName FROM  staffinfo WHERE memberID = $memberID");
            $numRows = $getStaffID->num_rows;
            if ($numRows == 0) {
                return '<div class="small-heading input-error-msg">Invalid Permissions.</div>';
            }
            $staffResult = $getStaffID->fetch_assoc();
            $_SESSION["user_id"] = $staffResult["staffID"];
            $_SESSION["name"] = $staffResult["firstName"].' '.$staffResult["middleName"].' '.$staffResult["lastName"];
            $_SESSION['random_code'] = StaffMember::random_code();
            return true;
          } 
          else 
          {
            return false;
          }
        }
    }


    public static function LogOut() {
     unset($_SESSION['user_id']);
                unset($_SESSION['name']);
                                unset($_SESSION['random_code']);


                // session_unset(); 
                // session_destroy();
                header("Location:login.php");
     }
    public static function random_code($length = 8) {
        // 密码字符集，可任意添加你需要的字符
       $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
            //$chars = 'abcdefghijklmnopqrstuvwxyz';
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        return $password;
    }
    //new changes
    // public static function timeout()
    // {
    //     $_SESSION['current_time']=time();
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
            echo "<pre>";
            print_r($array);
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
                                $mail->Body    ='Password reset link here :http://45.76.160.28:4003/Question/resetPassword.php?token='.$array[0]['token'];
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

        public static function score($score,$mini_score)
    {


          // $query="SELECT 72daccounts.username from staffinfo
          //   LEFT JOIN 72daccounts ON 72daccounts.memberID=staffinfo.memberID    
          //  where staffinfo.staffID=?";
            $query="SELECT email from staffinfo
           where staffID=?";
            $result=db::$con->prepare($query);
            $result->bind_param('i',$_SESSION['user_id']);
           $result->execute();
            $get=$result->get_result();
            while($row=$get->fetch_assoc())
            {
                $array[]=$row;
            }
            if(!empty($array))
            {

                if($score>=80)
                {
                    $status="Pass";
                }
                else
                {
                    $status="Fail";
                }
                


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
                                $mail->setFrom('siddhant@72dragons.com', '72 dragons');
                                $mail->addAddress($array[0]['email']);     // Add a recipient
                              
                                
                                // Content
                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = 'Online Training Tool Result';
                                $mail->Body    ='Your score is '.$score.' and status : '.$status.'<br>Your mini score is '.$mini_score;
                                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

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

    public static function reset_password($token,$password,$re_password)
        {
                if($password==$re_password)
                {

                     $newHash = password_hash($password, PASSWORD_DEFAULT);
               $result=db::$con->query("UPDATE 72daccounts SET password ='".$newHash."' WHERE token  ='".$token."'");
                    
                     if($result)
                     {
                         echo "yes";
                     }
                     else
                     {
                        echo "no";
                     }
                }
                else
                {
                         echo "no";
                }
        }

}

// $_GET['api']='forgot_pwd';
// $_POST['email']="siddhantsawant29@gmail.com";


if(!empty($_GET['api'])){
  if($_GET['api'] == 'login'){
        // echo json_encode(StaffMember::loginStaff($_POST['username'],$_POST['password']));
              echo json_encode(StaffMember::loginStaff($_POST['username'],$_POST['password']));
    }elseif($_GET['api'] == 'LogOut'){
      echo json_encode(StaffMember::LogOut());
    }
        elseif($_GET['api']=='forgot_pwd')
    {
             StaffMember::forgot_pwd($_POST['email']);
    }
    elseif($_GET['api']=='reset_password')
    {
            StaffMember::reset_password($_POST['token'],$_POST['pass1'],$_POST['pass2']);
    }
    // elseif($_GET['api']=='score')
    // {
    //         StaffMember::score($_POST['score'],$_POST['id']);
    // }
        elseif($_GET['api']=='score')
    {
            StaffMember::score($_POST['winnerVal_fraction'],$_POST['winnerVal_mini']);
    }
}

?>