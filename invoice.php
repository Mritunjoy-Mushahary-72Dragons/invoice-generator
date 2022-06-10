<?php 
session_start();
include "api.php";
if(isset($_GET['invoice_id']))
{
      $obj=new sheet();
      $_POST['invoice_id']=$_GET['invoice_id'];
      $main_data=$obj->list_invoice($_POST);
//    echo "<pre>";   print_r($main_data);
}
else
{
  // echo "d";
  //header("Location:/login.php");
}
if(!isset($_SESSION['user_id']))
{
  header("Location:login.php");
}
 ?>
<!DOCTYPE html>
<html>
<head>
        <title></title>
           <link rel="stylesheet" href="css/invoice.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"
    integrity="sha256-PF6MatZtiJ8/c9O9HQ8uSUXr++R9KBYu4gbNG5511WE=" crossorigin="anonymous" />
  <link rel="stylesheet" href="/css/all.min.css" >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
    <body class="inv">
                <div>
                  
                    <h2> Invoice Generator</h2>
                   
                </div>
                <button style="background-color:gold; float:right; margin-top: 10px; margin-right:20px;padding:5px">
                  <a  style="float:right ; text-decoration:none; color:black;background-color:gold ;border: radius 2px;" href="login-api.php?api=LogOut">Logout</a>
                </button>

                
                <button  style="background-color:gold;  margin-top: 10px;    margin-left:700px; margin-right:20px;padding:10px"     onclick="window.location.href='invoicelist.page.php'"  class="btn"><i class="fa fa-bars">
                  
                </i> Invoice List</button>




<input type="hidden" name="bill_id" value="<?php echo $_GET['invoice_id']; ?>">
        <div class="input-group">
                <label class="profile-info">Date:</label>
                <?php 
               if(isset($_GET['invoice_id']))
                  { 
                echo '<input type="date" name="date" id="date" value="'.$main_data[0]["dates"].'" style="padding: 3px;width: 99%;font-size: 16px;opacity: 0.8;" readonly>';
                  }
                  else
                  {
           echo '<input type="date" name="date" id="date" value="" style="margin-bottom: 34px;padding: 3px; width:99%; font-size: 16px">';
                  }
                              ?>
                   
       
      
                <label class="profile-info">Billed from:</label>
                <select id="selectbox0" class="selectbox" name="Billed_from">
                  
                  <?php 
                  if(isset($_GET['invoice_id']))
                  {
                              ?>
                                <option value="<?php echo $main_data[0]['billed_from_id']; ?>"><?php echo $main_data[0]['billed_from']; ?></option>
                    <?php
                      $obj=new sheet();
                      $_POST['billed_from']=$main_data[0]['billed_from_id'];
                      $data=$obj->address($_POST);
                      foreach ($data as $key => $value) {
                                ?>
                                <option value="<?php echo $value['addr_id']; ?>"><?php echo $value['address']; ?></option>
                               <?php 
                                }
                            }
                  else
                  {
                    ?>
                                <option value="0">Select&hellip;</option>
                                <?php 
                                $obj=new sheet();
                                  $_POST['billed_from']="";
                                $data=$obj->address($_POST);
                                foreach ($data as $key => $value) {
                                ?>
                                <option value="<?php echo $value['addr_id']; ?>"><?php echo $value['address']; ?></option>
                    <?php 
                        }                     
                  }
                   ?>  
                    
                </select><br><br>
         
    
                <label class="profile-info">Country:</label>
                <select id="cmp_country" class="selectbox" name="Select_Country" onchange="country()">
                   
                        <?php 
                  if(isset($_GET['invoice_id']))
                  {
                              ?>
                                <option value="<?php echo $main_data[0]['Currency_id']; ?>"><?php echo $main_data[0]['Country']; ?></option>
                    <?php
                      $obj=new sheet();
                      $_POST['Country']=$main_data[0]['Currency_id'];
                      $data=$obj->country($_POST);
                       
                      foreach ($data as $key => $value) {
                                ?>
                                <option value="<?php echo $value['Currency_id']; ?>"><?php echo $value['Country']; ?></option>
                               <?php 
                                }
                            }
                  else
                  {
                    ?>
                                <option value="0">Select&hellip;</option>
                                <?php 
                                $obj=new sheet();
                                  $_POST['Country']="";
                                $data=$obj->country($_POST);
                                foreach ($data as $key => $value) {
                                ?>
                               <option value="<?php echo $value['Currency_id']; ?>"><?php echo $value['Country']; ?></option>
                    <?php 
                        }                     
                  }
                   ?>  
                    </select><br><br>
                <label class="container">Do you want to generator invoice for particular user
                <?php 
                  if(isset($_GET['invoice_id']))
                  {
                    if(!empty($main_data[0]['Username']))
                    {
                        echo '<input type="checkbox" id="check" name="checkbox" value="" checked >';
                    }
                    else
                    {
                        echo '<input type="checkbox" id="check" name="checkbox"  value="" >';   
                    }
                  }
                  else
                  {
              echo '<input type="checkbox" id="check" name="checkbox" value="">';                    
                  }
                 ?>
                <span class="mark"></span></label>
                <!-- display user payment -->
                <?php 
                  if(isset($_GET['invoice_id']))
                  {
                      
                                                                if(!empty($main_data[0]['Username']))
                                                                {
                                                                echo '<div class="main"><div id="box" style="margin-bottom:-44px;">
                                                                    <span class="profile-info myClass">User:
                                                                    <select  id="user_list" class="selectbox" name="Select_B1">
                                                                      <option value="'.$main_data[0]['Username'].'">'.$main_data[0]['username'].'</option>';
                                                
                                                                $data2=$obj->update_user_list($main_data[0]['Username'],$main_data[0]['Currency_id']);
                                                                      foreach ($data2 as $key2 => $value2)
                                                                             {
                                                                      echo "<option value=".$value2['user_id'].">".$value2['username']."</option>";
                                                                                                    }      
                                                                       echo '</select></span><br><br>
                                                                    
                                                                     <span class="profile-info myClass">Type of payment2:
                                                                    <select  id="cmp_list" class="selectbox" name="Select_B2">
                                                                    <option value="'.$main_data[0]['sign_id'].'">'.$main_data[0]['sign_long'].'</option>';
                                                                     $obj=new sheet();
                                                                    $_POST['sign_id']=$main_data[0]['sign_id'];
                                                                    $_POST['Currency_id']=$main_data[0]['Currency_id'];
                                                                    $_POST['for_user']="1";
                                                                    $data=$obj->sign_list($_POST);
                                                                      foreach ($data as $key => $value)
                                                                            {
                                                                     echo "<option value=".$value['sign_id'].">".$value['sign_long']."</option>";
                                                                            }           
                                                                            echo '</select></span></div></div><br><br>';                                                                   
                                                                }
                                                                else
                                                                {
                                                                echo '<div class="main"><div class="pymt_cmpy" style="margin-top:20px;">
                                                                    <span class="profile-info myClass1">Type of payment:
                                                                    <select id="cmp_list" class="selectbox" name="Select_Box" style="margin-bottom: -5px;">
                                                                     <option value="'.$main_data[0]['sign_id'].'">'.$main_data[0]['sign_long'].'</option>';
                                                                    $obj=new sheet();
                                                                    $_POST['sign_id']=$main_data[0]['sign_id'];
                                                                    $_POST['Currency_id']=$main_data[0]['Currency_id'];
                                                                    $_POST['for_cmp']="1";
                                                                    $data=$obj->sign_list($_POST);
                                                                      foreach ($data as $key => $value)
                                                                            {
                                                                     echo "<option value=".$value['sign_id'].">".$value['sign_long']."</option>";
                                                                            }           
                                                                            echo "</select></span></div></div><br><br>";
                                                                }        
                    }
                    else
                    {
                        echo '<div class="main"></div><br><br>';
                    }
?>
                   <label class="profile-info">Description: <span class="astr" style="color: #96031a;">*</span></label>
                    <?php 
                    if(isset($_GET['invoice_id']))
                  {
    echo '<input type="text" class="inputBox" placeholder="" value="'.$main_data[0]['description']['des_title'].'" name="Description" style=" width: 99%;" required/><br />';
}
else
{
    echo '<input type="text" class="inputBox" placeholder="" value="" name="Description" style=" width: 99%;" required/><br />';
}
                     ?>
                    
                    
                    <label class="profile-info">Amount: <span class="astr" style="color: #96031a;">*</span></label>
                    <?php 
                    if(isset($_GET['invoice_id']))
                  {
    echo '<input type="number" min="0" class="inputBox Amt" placeholder="" value="'.$main_data[0]['description']['amount'].'" name="Amount" style=" width: 99%;" required /><br />';
}
else
{
    echo '<input type="number" min="0"  class="inputBox Amt" placeholder="" value="" name="Amount" style=" width: 99%;" required /><br />';
}
                     ?>
                    <div class="peer-input-field">
                   <?php 
                    if(isset($_GET['invoice_id']))
                    {
                            if(!empty($main_data[0]['description']['sub_description']))
                            {
                                foreach ($main_data[0]['description']['sub_description'] as $key => $value) {
                                    echo '<div class="intension-container" style="margin-left:50px;">
                        <label class="profile-info">Description: <span class="astr" style="color: #96031a;">*</span></label>
                        <input type="text" class="inputBox Des" placeholder="" value="'.$value['sub_title'].'" name="Des" style=" width: 99%;" /><i class="fas fa-times cross-peer"></i><br />
                        <label class="profile-info">Amount: <span class="astr" style="color: #96031a;">*</span></label>
                        <input type="number"  min="0" class="inputBox Amt" placeholder="" value="'.$value['amount'].'" name="Amt" style=" width: 99%;" /><br />
                    </div>';
                                }
                            }
                    }
                    ?>
                    </div>
                    <div class="add-peer-input">
                        <i class="fas fa-plus add-peer" style="margin-bottom: 20px;">
                            <span class="inc-0">Add Another Payment</span></i>
                    </div>
           
                <div class="popButton">
                    <button type="submit" class="user-btn cancel-user">Cancel</button>
                    <button type="submit" class="user-btn save-user">Submit</button>
                    <button type="submit" class="user-btn bill-btn"><a href="http://45.76.160.28:5000/Invoice_sheet/bill_detail.php" style="text-decoration: none;color: #000000;">Bill Detail</a></button>
                </div>
        </div>
    
    
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <?php 
if(!isset($_GET['invoice_id']))
                  {
echo '<script src="js/main.js"></script>';
}
else
{
    echo '<script src="js/update.js"></script>';
}
 ?>
    </body>
</html>
