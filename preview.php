<?php include "templates/header.php"; ?>


                    <a class="new" href="invoice.php">Create a New Invoice</a>
<center>
<div id="main">
<?php
include "api.php";
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
