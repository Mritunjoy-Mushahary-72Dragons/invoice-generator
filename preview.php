<?php include "templates/header.php"; ?>

<!-- 
    ----------------------------------------------
    HEADER CODE - START
    ---------------------------------------------- 
-->
<!-- THIS IS WORKING -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview page</title>
</head>
<style>
    background
</style>
<body>
<div class="header">
        <button style="padding:8px; border: 1px solid black;background-color: #ae943f; border-radius: 8px; ">
            <a style="text-decoration:none; color: black;" href="#"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</a>
        </button>


        <h1 style=" margin: auto;
   width: 200px; font-weight: 900; color: #ae943f; ">
            <i class="fas fa-file-invoice"></i>

            Invoice List Page

        </h1>
    </div>
</body>
</html>


<!-- 
    ----------------------------------------------
    HEADER CODE - END
    ---------------------------------------------- 
-->






                    <!-- <a class="new" href="invoice.php">Create a New Invoice</a>
<center>
<div id="main"> -->
<?php
//include "api.php";
if(isset($_GET['invoice_id']))
{
//print_r($_GET);


                  $obj=new sheet();
                  $_POST['invoice_id']=$_GET['invoice_id'];
                  $main_data=$obj->list_invoice($_POST);
                  $table= $obj->table_insert($main_data);


                  // echo "<pre>";
                  print_r($table);
}
?>
</div>
</center>
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">


function del(id)
{

    $.ajax({
                        url: "api.php?api=del_invoice",
                        type: "POST",
                        data: {invoice_id:id},
                        success: function (data) {
                        $("#main").append(data);
                        console.log(data);
                        if(data)
                        {
                            alert("Invoice Bill Is Deleted");
                  window.location="invoice.php";
                  }
                        else
                        {
                         alert("Invoice Bill Is Not Deleted");
                        }
                        },
                        error: function (data) {
                            alert(
                                "An error has occcured while adding Object Details.Please try again"
                            );
                        }
                    });

}


</script>
</body>
</html>
