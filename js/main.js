            $(document).ready(function (){
                $(".add-peer").click(function () {
                    $(".peer-input-field").append(`
                    <div class="intension-container">
                        <label class="profile-info">Sub Description: <span class="astr" style="color: #96031a;">*</span></label>
                        <input type="text" class="inputBox Des" placeholder="" value="" name="Des" style=" width: 98%;" /><i class="fas fa-times cross-peer"></i><br />
                        <label class="profile-info">Amount: <span class="astr" style="color: #96031a;">*</span></label>
                        <input type="number" min="0" class="inputBox Amt" placeholder="" value="" name="Amt" style="width: 98%;" /><br />
                    </div>`);
                });


                $("body").on("click", ".cross-peer", function (e) {
                    $(this)
                        .closest(".peer-input-field .intension-container")
                        .last()
                        .remove();
                });

                // cancel button
                    var cancelBtn = document.querySelector('.cancel-user');
                    cancelBtn.addEventListener('click', function () {
                        window.location.href = 'invoice.php';
                    });

                // AJAX for submitting object details
                
                $("body").on("click", ".save-user", function (e) {
                    e.preventDefault();

                 
                    var date = $("input[name='date']").val();
                    var Billed_from = $("select[name='Billed_from']").val();
                    var Select_Country = $("select[name='Select_Country']").val();
                    var Select_B1 = $("select[name='Select_B1']").val();
                    var Select_B2 = $("select[name='Select_B2']").val();
                    var Select_Box = $("select[name='Select_Box']").val();
                  
                    var Description = $("input[name='Description']").val();
                    var Amount = $("input[name='Amount']").val();


                      var Des = $("input[name='Des']").val();
                      var Amt = $("input[name='Amt']").val();
                   
                   

                    if (date == "") {
                        alert("Please fill the date");
                        return false;
                    }
                    if (Billed_from == "") {
                        alert("Please fill the address");
                        return false;

                    }
                    if (Billed_from == "0") {
                        alert("Please fill the proper address");
                        return false;

                    }


                    if (Select_Country == "0") {
                        alert("Please fill the proper country");
                        return false;

                    }
                    if (Select_Country == "") {
                        alert("Please fill the  country");
                        return false;

                    }

                    
                    if (Select_B1 == "") {
                        alert("Please fill the user");
                        return false;
                    }
                    if (Select_B1 == "0") {
                        alert("Please fill the proper user");
                        return false;
                    }
                    if (Select_B2 == "") {
                        alert("Please fill the payment");
                        return false;
                    }

                    if (Select_B2 == "0") {
                        alert("Please fill the proper payment");
                        return false;
                    }
                    if (Select_Box == "") {
                        alert("Please fill the company payment");
                        return false;
                    }
                    if (Select_Box == "0") {
                        alert("Please fill the proper company payment");
                        return false;
                    }

                    if (Description == "") {
                        alert('Please fill the Description');
                        return false;
                    }
                    if (Amount == "") {
                        alert("Please fill the amount");
                        return false;
                        }
                    if (Des == "") {
                        alert('Please fill the sub Description');
                        return false;
                    }
                    if (Amt == "") {
                        alert("Please fill the sub amount");
                        return false;
                    }




                    var formData = new FormData();
                        formData.append('dates', date);
                        formData.append('billed_from', Billed_from);
                        formData.append('country', Select_Country);
                        formData.append('username', Select_B1);
                        formData.append('type_of_payment', Select_B2);
                        formData.append('type_of_payment_cmp', Select_Box);

                        formData.append('description[]', Description);
                        formData.append('amount[]', Amount);
                        formData.append('desc', Des);
                        formData.append('amot', Amt);
            

                            var output1;
                            var output2;
                            $(".peer-input-field .intension-container").each(function () {
                                output1 = $(this).find(".Des");
                                output2 = $(this).find(".Amt");
                                formData.append('desc[]', output1.val());
                                formData.append('amot[]', output2.val());
                            });
                            // if ($(output1).val() == "") {
                            //     alert('Please fill the Descriptions');
                            //     return false;
                            // }
                            // if ($(output2).val() == "") {
                            //     alert('Please fill the Amount');
                            //    return false;
                            // }

                    $.ajax({
                       url: "api.php?api=insert_invoice",
                //        url: "rough/object.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            console.log(data);
                            if(data=="false")
                            {
                            alert("Your sub description total does not match with main description amount");                                
                            }
                           else if(data=='already_user')
                            {
                                alert("This bill is already created for particular address,country,user,type of payment and for particular date");
                            }
                             else if(data=='already_company')
                            {
                                alert("This bill is already created for particular address,country,type of payment and for particular date");
                            }
                            else
                            {

                            alert("Submitted");
                       // window.location="invoice.php";
                        window.location="preview.php?invoice_id="+data;
                            }
                        },
                        error: function (data) {
                            alert(
                                "An error has occcured while adding Object Details.Please try again"
                            );
                        }

                    });
                    return false;

                });

            });

    // Checkbox jquery
function country(){
$("#cmp_list").empty();
$("#user_list").empty();

var country_id = document.getElementById("cmp_country").value;
var chk_send=$('#check').is(':checked');
console.log(country_id);
console.log(chk_send);
                    $.ajax({
                        url: "api.php?api=crty_list",
                        type: "POST",
                        data: {country:country_id,chk_send:chk_send},
                        success: function (data) {
                        $("#cmp_list").append(data);
                        console.log(data);
                        },
                        error: function (data) {
                            alert(
                                "An error has occcured while adding Object Details.Please try again"
                            );
                        }
                    });

                     $.ajax({
                        url: "api.php?api=user_list",
                        type: "POST",
                        data: {country:country_id},
                        success: function (data) {
                       $("#user_list").append(data);
                        console.log(data);
                        },
                        error: function (data) {
                            alert(
                                "An error has occcured while adding Object Details.Please try again"
                            );
                        }
                    });
}

$(document).ready(function() {
var cmp_html='<div class="pymt_cmpy" style="margin-top: 20px;"><span class="profile-info myClass1">Type of payment:<select id="cmp_list" class="selectbox" name="Select_Box" style="margin-bottom: -5px;"></select></span></div>';

var html='<div id="box" style="margin-bottom:24px 0px -14px -1px;"><span class="profile-info myClass">User:<select  id="user_list" class="selectbox" name="Select_B1"></select></span><br><br><span class="profile-info myClass">Type of payment:<select  id="cmp_list" class="selectbox" name="Select_B2"></select></span></div>';

$("#box").remove();
$(".main").append(cmp_html);

    $("#check").change(function(){
    if($(this).is(':checked'))
    {
    // alert('Checked!');
        $(".pymt_cmpy").remove();  
        $(".main").append(html);

          var country_id = document.getElementById("cmp_country").value;
          var chk_send=true;
       // alert(country_id);
           $.ajax({
                        url: "api.php?api=crty_list",
                        type: "POST",
                        data: {country:country_id,chk_send:chk_send},
                        success: function (data) {
                       $("#cmp_list").append(data);
                        console.log(data);
                        },
                        error: function (data) {
                            alert(
                                "An error has occcured while adding Object Details.Please try again"
                            );
                        }
                    });


            $.ajax({
                        url: "api.php?api=user_list",
                        type: "POST",
                        data: {country:country_id},
                        success: function (data) {
                       $("#user_list").append(data);
                        console.log(data);
                        },
                        error: function (data) {
                            alert(
                                "An error has occcured while adding Object Details.Please try again"
                            );
                        }
                    });

    }
    else
    {
        $("#box").remove();  
        $(".main").append(cmp_html);
        country();
    }
    });




        });

