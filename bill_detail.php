<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/table.css">
	<title></title>
    
<!-- 	<style type="text/css">
     #dis
    {position: absolute;
    top: 2px;
    left: 868px; 
    height: 15px;
    width: 15px;
    background: transparent;
    border: 1px solid #ad9440;   
    }
    </style>
</head>
<body>
	<label class="container" style="margin: 20px;padding-bottom: 20px;text-align: center;">Display Invoice For Particular User
				<input type="checkbox" id="check" name="checkbox" value="">
                <span class="mark" id="dis"></span>
                <a class="new" style="float: right;
background-color: #ae943f;
    border: 1px solid #ae943f;
    border-radius: 2px;
    padding: 5px 9px;" href="invoice.php">New</a>
    </label>
<center>
<div id="main" style="width:50%;"></div>
</center> -->
<!-- <style type="text/css">
        body{
            margin: 0px;
            padding: 0px;
             background-color: #96031a;
        }
     #dis
    {
        position: absolute;
        top: 43px;
        left: 632px;
        height: 15px;
        width: 15px;
        background: transparent;
        border: 1px solid #ad9440;   
    }
    .new{
        float: right;text-decoration: none;
        background-color: #ae943f;
        border: 1px solid #ae943f;
        border-radius: 2px;
        padding: 5px 9px;
        margin-right: 20px;
        color: #000000;
    }

   .main_first , .second_main{
    background: white;
   }
    </style>
</head>
<body>
    <div id="home">
           <label class="container" style="padding-top: 40px;margin-left: 148px;text-align: center;">Display Invoice For Particular User
                <input type="checkbox" id="check" name="checkbox" value="">
                <span class="mark" id="dis"></span>
             <a class="new" href="invoice.php">Create a New Invoice</a>
            </label>
            <center>
            <div id="main" style="width:50%;"></div>
            </center>
    </div> -->
    <style type="text/css">
        body{
            margin: 0px;
            padding: 0px;
             background-color: #96031a;
        }
  
    .new{
        float: right;text-decoration: none;
        background-color: #ae943f;
        border: 1px solid #ae943f;
        border-radius: 2px;
        padding: 5px 9px;
        margin-right: 20px;
        color: #000000;
    }
    #main{
        width:50%;
    }
   .main_first , .second_main{
    background: white;
   }
   .container{
   padding-top: 40px;
   margin-left: 148px;
   text-align: center;
   }


   .loader
   {
    display: none;
    height: 100vh;
    width: 100vw;
    overflow: hidden;
    background-color: #96031a;
    position: absolute;
    opacity: 0.8;
   }

   .loader>div#roll{
    height: 60px;
    width: 60px;
    border: 15px solid black;
    border-top-color: #ae943f;
    position: absolute;
    margin: auto;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: 50%;
    animation: spin 1.5s infinite linear;
   }



   @keyframes spin{
    100%{
        transform: rotate(360deg);
    }
   }



@media screen and (max-device-width: 768px){
       
        .new {
           margin-right: 32px;
            margin-top: -26px;
            font-size: 11px;
        }
        .mark {
            margin-top: 1px;
            margin-left: -80px;

        }    
      #main{
           width: 72%;
        margin-left: -68px  
      }
      .container{
       margin-left: -87px;
      }

        .main_first , .second_main{
            width: 114%;
       }
       .btn-sm {
    margin-left: 85px;
    }
}

@media screen and (min-device-width: 414px) and (max-device-width: 768px){

        #main {
         /*   display: none;*/
            width: 84%;
            margin-left: -59px;
        }
        .mark{
            margin-left: -11px;
        }
}

</style>
</head>
<body>
 <div class="loader">
                    <div id="roll"></div>
                    </div>
    <div id="home">
           <label class="container">Display Invoice For Particular User
                <input type="checkbox" id="check" name="checkbox" value="">
                <span class="mark"></span>
             <a class="new" href="invoice.php">Create a New Invoice</a>
            </label>
               
            <center>
            <div id="main"></div>
            </center>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
// $(window).on('load',function(){
// $('.loader').fadeOut(5000);
// $('body').fadeIn(5000);
// });


function sign(){

            var country_id = document.getElementById("cmp_country").value;
            $(".final_list").remove();
            $("#cmp_pymt").empty();
            $("#cmp_dates").empty();

            $.ajax({
            url: "api.php?api=cmp_paymt_list",
            type: "POST",
            data: {country:country_id},
            success: function (data) {
            $("#cmp_pymt").append(data);
            console.log(data);
            },
            error: function (data) {
            alert(
            "An error has occcured while adding Object Details.Please try again"
            );
            }
            });
}
function dates() {
      $(".main_first").remove();
                    $(".second_main").remove();
                    $(".btn-sm").remove();
    $(".final_list").remove();
    $("#cmp_dates").empty();
    var country_id = document.getElementById("cmp_country").value;
    var type_of_payment = document.getElementById("cmp_pymt").value;
    $.ajax({
    url: "api.php?api=cmp_paymt_list",
    type: "POST",
    data: {country:country_id,type_of_payment:type_of_payment},
    success: function (data) {
    $("#cmp_dates").append(data);
    console.log(data);
    },
    error: function (data) {
    alert(
    "An error has occcured while adding Object Details.Please try again"
    );
    }
    });
}
function user() {
        $(".final_list").remove();
        $("#user").empty();
        $("#dates").empty();
        $("#pymt").empty();
        var country_id = document.getElementById("country").value;
        console.log(country_id);
        $.ajax({
        url: "api.php?api=paymt_list",
        type: "POST",
        data: {country:country_id},
        success: function (data) {
        $("#user").append(data);
        console.log(data);
        },
        error: function (data) {
        alert(
        "An error has occcured while adding Object Details.Please try again"
        );
        }
        });
}
function pymt() {

            $(".final_list").remove();
            $("#dates").empty();
            $("#pymt").empty();
            var country_id = document.getElementById("country").value;
            var user_id = document.getElementById("user").value;
            console.log(country_id);
            $.ajax({
            url: "api.php?api=paymt_list",
            type: "POST",
            data: {country:country_id,Username:user_id},
            success: function (data) {
            $("#pymt").append(data);
            console.log(data);
            },
            error: function (data) {
            alert(
            "An error has occcured while adding Object Details.Please try again"
            );
            }
            });
}



function user_dates(){
                $(".final_list").remove();
                $("#dates").empty();
                var country_id = document.getElementById("country").value;
                var user_id = document.getElementById("user").value;
                var pymt = document.getElementById("pymt").value;
                console.log(country_id);
                $.ajax({
                url: "api.php?api=paymt_list",
                type: "POST",
                data: {country:country_id,Username:user_id,type_of_payment:pymt},
                success: function (data) {
                $("#dates").append(data);

                },
                error: function (data) {
                alert(
                "An error has occcured while adding Object Details.Please try again"
                );
                }
                });
            }



function final_user_data(){
$('.loader').css('display','block').hide(3000);

                    var country_id = document.getElementById("country").value;
                    var user_id = document.getElementById("user").value;
                    var pymt = document.getElementById("pymt").value;
                    var dates = document.getElementById("dates").value;



                    const myTimeout = setTimeout(ajax_run, 3000);

                    function ajax_run() {
                    $.ajax({
                    url: "api.php?api=list_invoice",
                    type: "POST",
                    data: {country:country_id,Username:user_id,type_of_payment:pymt,dates:dates},
                    success: function (data) {
                    $(".main_first").remove();
                    $(".second_main").remove();
                    $(".btn-sm").remove();
                    $("#main").append(data);
                    console.log(data);
                    },
                    error: function (data) {
                    alert(
                    "An error has occcured while adding Object Details.Please try again"
                    );
                    }
                    });
                    }
}


function final_cmpy_data(){
$('.loader').css('display','block').hide(3000);
//$('body').fadeIn(3000);
            var country_id = document.getElementById("cmp_country").value;
            var pymt = document.getElementById("cmp_pymt").value;
            var dates = document.getElementById("cmp_dates").value;


            const myTimeout = setTimeout(ajax_run, 2000);

            function ajax_run() {
            $.ajax({
            url: "api.php?api=list_invoice",
            type: "POST",
            data: {country:country_id,type_of_payment:pymt,dates:dates},
            success: function (data) {
            $(".main_first").remove();
            $(".second_main").remove();
            $(".btn-sm").remove();
            $("#main").append(data);
            console.log(data);
            },
            error: function (data) {
            alert(
            "An error has occcured while adding Object Details.Please try again"
            );
            }
            });
            }
}


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
                            location.reload();
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
$(document).ready(function() {
var cmp_html='<div id="pymt_cmpy" style="margin-top: -20px;"><span class="profile-info myClass1">Country:<select  id="cmp_country" class="selectbox" name="Select_B1" onchange="sign()"><option value="0">Select</option><option value="1">USA</option><option value="2">China</option><option value="3">India</option><option value="4">Hong Kong</option></select></span><span class="profile-info myClass1">Type of payment:<select id="cmp_pymt" class="selectbox" name="Select_Box" style="" onchange="dates()"></select></span><span class="profile-info myClass1">Date:<select id="cmp_dates" class="selectbox" name="" style="margin-bottom: -5px;" onchange="final_cmpy_data()"></select></span></div>';
 var html='<div id="box" style="margin-bottom:-44px;"><span class="profile-info myClass">Country:<select  id="country" class="selectbox" name="Select_B1" onchange="user()"><option value="0">Select</option><option value="1">USA</option><option value="2">China</option><option value="3">India</option><option value="4">Hong Kong</option></select></span><span class="profile-info myClass">User:<select  id="user" class="selectbox" name="Select_B1" onchange="pymt()"><option value="0">Select</option></select></span><br><br><span class="profile-info myClass">Type of payment:<select  id="pymt" class="selectbox" name="Select_B2" onchange="user_dates()"><option value="0">Select</option></select></span><span class="profile-info myClass">Date:<select id="dates" onchange="final_user_data()"><option value="0">Select</option></select></span></div>';
$("#box").remove();
$("#main").append(cmp_html);
    $("#check").change(function(){
    if($(this).is(':checked'))
    {
                         $("#main").empty();
 
        $("#pymt_cmpy").remove();  
         $("#main").append(html);
}
    else
    {
                 $("#main").empty();
        $("#box").remove();  
        $("#main").append(cmp_html);
        
    }
    });
        });
  
</script>
</body>
</html>