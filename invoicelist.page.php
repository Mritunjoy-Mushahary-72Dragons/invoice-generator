<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=], initial-scale=1.0">
    <title>Invoice page</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" type="text/css" href="//use.fontawesome.com/releases/v5.7.2/css/all.css">


</head>

<body>











    <!-- css for header -->






    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .header {
            overflow: hidden;
            background-color: #800000;
            padding: 20px 10px;
        }

        .header h1 {
            margin-right: 100px;
            float: right;
            color: black;
            font-size: 18px;

        }









        @media screen and (max-width: 500px) {
    </style>



    <!-- header for css ends -->

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;500&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            background-color: black;
        }

        body>div {
            width: 100%;
            height: auto;
            display: flex;
            font-family: 'Roboto', sans-serif;
        }

        .table_responsive {
            max-width: 800px;
            border: 1px solid #00bcd4;
            background-color: #efefef33;
            padding: 5px;
            overflow: auto;
            margin: auto;
            border-radius: 4px;
        }





        @media only screen and (max-width: 700px) {
            .table_responsive {
                max-width: 400px;
                border: 1px solid #00bcd4;
                background-color: #efefef33;
                padding: 1px;
                overflow: auto;
                margin: auto;
                border-radius: 4px;
            }


            table th,
        table td {
            border: 1px solid #00000017;
            padding: 10px 15px;
        }

        }


        table {
            width: 100%;
            font-size: 13px;
            color: #ae943f;
            white-space: nowrap;
            border-collapse: collapse;
        }

        table>thead {
            background-color: #800000;
            ;
            color: #fff;
            height: 150px;
        }

        table>thead th {
            padding: 15px;
        }

        table th,
        table td {
            border: 1px solid #00000017;
            padding: 10px 15px;
        }

        table>tbody>tr>td>img {
            display: inline-block;
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 2px 6px #0003;
        }

        .action_btn {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action_btn>a {
            text-decoration: none;
            color: #444;
            background: #fff;
            border: 1px solid;
            display: inline-block;
            padding: 7px 20px;
            font-weight: bold;
            border-radius: 3px;
            transition: 0.3s ease-in-out;
        }

        .action_btn>a:nth-child(1) {
            border-color: #26a69a;
        }

        .action_btn>a:nth-child(2) {
            border-color: orange;
        }

        .action_btn>a:hover {
            box-shadow: 0 3px 8px #0003;
        }

        table>tbody>tr {
            background-color: #fff;
            transition: 0.3s ease-in-out;
        }

        table>tbody>tr:nth-child(even) {
            background-color: rgb(238, 238, 238);
        }

        table>tbody>tr:hover {
            filter: drop-shadow(0px 2px 6px #0002);
        }
    </style>




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



    <style>
        #icon {
            vertical-align: middle;
            font-size: 40px;
        }
    </style>





    <br>
    <br>
    <br>









    <div class="table_responsive">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Changes</th>
                   
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>xyz</td>
                    <td>xyz</td>
                    <td>xyz</td>
                    



                    <td>
                        <span class="action_btn">
                            <a href="#"><i class="fas fa-edit"></i></a>
                            <a href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>

                        </span>
                    </td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>xyz</td>
                    <td>xyz</td>
                    <td>xyz</td>


                    <td>
                        <span class="action_btn">
                            <a href="#"><i class="fas fa-edit"></i></a>
                            <a href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>xyz</td>
                    <td>xyz</td>
                    <td>xyz</td>

                    <td>
                        <span class="action_btn">
                            <a href="#"><i class="fas fa-edit"></i></a>
                            <a href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>