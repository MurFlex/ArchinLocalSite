<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Archin</title>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito', sans-serif;
            height: 100vh;
        }

        .centered {
            position: fixed;
            top: 50%;
            left: 50%;
            font-weight: bold;
            font-size: 30px;
            transform: translate(-50%, -50%);
        }

        .wrap {
            display: flex;
            height: 75%;
            margin: 20px auto;
            border: 2px solid black;
            border-radius: 10px;
            max-width: 100em;
        }

        .header, .footer {
            /*margin: auto;*/
            min-width: 100vh;
            height: 10%;
            background-color: lightseagreen;
        }

        .table {
            border-right: 2px solid black;
            height: 100%;
            min-width: 80%;
        }

        .table_top {
            height: 10%;
            border-bottom: 2px solid black;
        }

        .search_form {
            margin: 0 auto;
            max-width: 20%;
            padding: 20px;
        }

        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            background-color: lightseagreen;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: seagreen;
        }

        div {
            /*border-radius: 5px;*/
            background-color: #f2f2f2;
            /*padding: 20px;*/
        }

        .table_top {

        }

        .table_header {
            height: 100%;
            padding: 1.5vh;
            font-size: 1.5vh;
        }

        .table_content {
            max-height: 89%;
            display: flex;
            flex-direction: column;
            overflow: auto
        }

        .table_element-1, .table_element-2 {
            min-height: 10vh;
            min-width: 99%;
            border-radius: 5px;
        }

        .table_element-1 {
            background-color: white;
            border: 1px solid black;
            margin: 1px auto;
        }

        .table_element-2 {
            border: 1px solid black;
            margin: 1px auto;
        }

        .content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product_bottom_buttons {
            width: 100%;
            background-color: lightseagreen;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px;
        }

        #list {
            list-style-type: none;
            padding: 14px 20px;
            margin: 10px;
        }

        #list > li {
        }

        #list > li > ul {
            list-style-type: none;
            display: none;
            padding-bottom: 2px;
        }
    </style>

</head>
<body>

@if (request()->ip() == '192.168.0.15')
<div class="header">

</div>

<div class="wrap">
    <div class="content">
        <div class="content_top">
            <h1 style="padding: 15px 0 0 15px;"> Device id: {{ $id }} </h1>
            <ul id="list">
                <li> Список приборов
                    <ul>
                        <li>sublist</li>
                        <li>sublist</li>
                        <li>sublist</li>
                        <li>sublist</li>
                        <li>sublist</li>
                        <li>sublist</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="content_bottom">
            <button class="product_bottom_buttons" type="button" onclick="history.back();">Back</button>
        </div>
    </div>

</div>



<div class="footer">

</div>

@else
<h class="centered"> Access denied </h>
@endif

<script>
    $(document).ready(function () {
        $('#list > li').click(function (event) {
            $(this).children("ul").slideToggle();
            event.stopPropagation();
        });
    });
</script>

</body>
</html>
