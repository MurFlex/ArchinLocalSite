<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Archin </title>
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
            display: flex;
            justify-content: flex-start;
            /*margin: auto;*/
            min-width: 100vh;
            height: 10%;
            background-color: #125ea8;
            color: white;
        }

        /*.footer {*/
        /*    position: absolute;*/
        /*    bottom: 0;*/
        /*    left: 0;*/
        /*}*/

        .table {
            border-right: 2px solid black;
            height: 100%;
            min-width: 80%;
        }

        .table_top {
            height: 10%;
            border-bottom: 2px solid black;
        }

        .search {
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
            background-color: #125ea8;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #125ee0;
        }

        .table_top {

        }

        .table_header {
            height: 100%;
            padding: 1.5vh;
            font-size: 1.75vh;
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
            background-color: #f2f2f2;
            border: 1px solid black;
            margin: 1px auto;
        }

        .header_elements, .footer_elements {
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            width: 80%;
            padding-top: 1.5em;
            font-size: 2vh;
            font-weight: bold;
        }

        .footer_element, .nav_item {
            cursor: pointer;
        }

        .search_form {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        form {
            min-height: 100%;
        }

        .nav_item {
            text-decoration: none;
        }

        a {
            color: white;
            text-decoration: none;
        }

        .product_bottom_buttons {
            width: 100%;
            background-color: #125ea8;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #list {
            list-style-type: none;
        }

        #list > li {
        }

        #list > li > ul {
            list-style-type: none;
            display: none;
            padding-bottom: 2px;
        }

        .content {
            width: 100%;
            margin: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .main_content {
            overflow: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid rgb(200, 200, 200);
            letter-spacing: 1px;
        }

        td {
            border: 2px solid black;
            padding: 10px;
        }
    </style>

</head>
<body>

<div class="header">
    <div class="header_elements">
        <div class="nav_item"> Логотип  </div>
        <div class="nav_item"> <a href="http://192.168.0.15/dev"> На главную </a> </div>
        @if (request()->ip() == '192.168.0.15') <div class="nav_item"> <a href="http://192.168.0.15/parse"> Admin </a> </div> @endif
        <div class="nav_item">  </div>
        <div class="nav_item">  </div>
        <div class="nav_item">  </div>
    </div>
</div>

<div class="wrap">
    <div class="content">
        <div class="content_top">
            <h1 style="margin-bottom: 10px"> Прибор №{{ $device['device_id'] }} </h1>
        </div>
        <div class="main_content">
{{--            @foreach($device as $index => $param)--}}
{{--                <p>{{ $index }} : {{ $param }} </p>--}}
{{--            @endforeach--}}
            <table>
                <tr style="font-weight: bold">
                    <td>
                        Наименование
                    </td>
                    <td>
                        Результат
                    </td>
                </tr>

                @foreach($device as $index => $param)
                    @if(isset($decoding[$index]))
                    <tr>
                        <td style="font-weight: bold">
                            {{ $decoding[$index] }}
                        </td>
                        <td>
                            @if($index == 'mitypeNumber')
                                <a style="color: black" href="{{ $device['mitypeURL'] }}">{{ $param }}</a>
                                @php
                                    continue;
                                @endphp
                            @endif
                            @if($index == 'device_id')
                                <a style="color: black" href="https://fgis.gost.ru/fundmetrology/cm/results/1-{{ $param }}">{{ $param }}</a>
                                @php
                                    continue;
                                @endphp
                            @endif
                            @if($param == 'Y' || $param == 'N')
                                {{ $param == 'Y' ? 'Да' : 'Нет' }}
                                @php
                                    continue;
                                @endphp
                            @endif
                            {{ $param == '' || $param == '0' ? 'Нет данных' : $param }}
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
        <div class="content_bottom">
            <button class="product_bottom_buttons" type="button" onclick="history.back();"> Назад </button>
        </div>
    </div>

</div>



<div class="footer">

</div>

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
