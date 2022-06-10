<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=], initial-scale=1.0">
    <title>Invoice page</title>


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
                padding: 15px;
                overflow: auto;
                margin: auto;
                border-radius: 4px;
            }

        }


        table {
            width: 100%;
            font-size: 13px;
            color: #ae943f;
            ;
            white-space: nowrap;
            border-collapse: collapse;
        }

        table>thead {
            background-color: #800000;
            ;
            color: #fff;
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

        <h1 style=" margin: auto;
   width: 200px; font-weight: 900; color: #ae943f; ">
            Invoice List Page
        </h1>
    </div>









    <br>
    <br>
    <br>









    <div class="table_responsive">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Changes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>

                    <td>xyz</td>


                    <td>
                        <span class="action_btn">
                            <a href="#">Edit</a>
                            <a href="#">Delete</a>
                            <a href="#">View</a>

                        </span>
                    </td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>xyz</td>


                    <td>
                        <span class="action_btn">
                            <a href="#">Edit</a>
                            <a href="#">Delete</a>
                            <a href="#">View</a>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>xyz</td>

                    <td>
                        <span class="action_btn">
                            <a href="#">Edit</a>
                            <a href="#">Delete</a>
                            <a href="#">View</a>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>