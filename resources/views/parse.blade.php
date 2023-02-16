<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
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
            min-width: 50%;
            /*padding: 20px;*/
            margin-right: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .nav_item {
            text-decoration: none;
        }

        a {
            color: white;
            text-decoration: none; /* no underline */
        }

        .product_bottom_buttons {
            width: 20vh;
            background-color: #125ea8;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .content_top {

        }

        .files_table {
            margin-right: 50px;
            overflow: auto;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .files_table_element {
            margin: 10px;
            padding: 10px;
        }

        .color {
            border-bottom: 2px solid black;
            /*border-radius: 5px;*/
        }
    </style>

</head>
<body>

@if (request()->ip() == '192.168.0.15' || request()->ip() == '192.168.0.22')
<div class="header">
    <div class="header_elements">
        <div class="nav_item"> Логотип  </div>
        <div class="nav_item"> <a href="http://192.168.0.15/dev"> На главную </a> </div>
        <div class="nav_item"> <a href="http://192.168.0.15/parse"> Парсинг </a> </div>
        <div class="nav_item">  </div>
        <div class="nav_item">  </div>
        <div class="nav_item">  </div>
    </div>
</div>

<div class="wrap">
    <div style='display: flex; flex-direction: row; justify-content: space-between; width: 100%; padding: 40px' class="content">
        <div style="display: flex; flex-direction: column; justify-content: space-between;" class="content_left">
            <div style="display: flex" class="content_top">
                <form action="#" method="get">
                        <div class="top_search_form">
                            <h2 style="margin-bottom: 10px"> Выгрузка приборов по категориям </h2>

                            <label for="from"> От </label>
                            <input type="text" id="from" name="from" placeholder="От" value="{{ $request['company_name'] ?? '' }}" required>

                            <label for="until"> До </label>
                            <input type="text" id="until" name="until" placeholder="До" value="{{ $request['device_name'] ?? '' }}" required>

                        </div>
                        <div class="submit_button">
                            <label style='visibility: hidden'> ъуъ </label>
                            <input id="submit" type="submit" value="Поиск">
                        </div>
                </form>

                <form action="#" method="get">
                    <div class="top_search_form">
                        <h2 style="margin-bottom: 10px"> Выгрузка прибора по id </h2>

                        <label style='visibility: hidden'> ъуъ </label>
                        <input type="text" id="search_id" name="id" placeholder="id прибора" value="{{ $request['company_name'] ?? '' }}" required>


                    </div>
                    <div class="submit_button">
                        <input type="submit" value="Поиск">
                    </div>
                </form>

            </div>
            <div class="content_bottom">
                <button class="product_bottom_buttons" type="button" onclick="history.back();"> Назад </button>
            </div>
        </div>
        <div class="content_right">
            <h2 style="margin-bottom: 10px"> Список файлов </h2>
            <div class="files_table">
                @if (isset( $files ))
                    @foreach ($files as $file)
                        <div class="files_table_element"> {{ $file }} </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
<div class="footer">

</div>
@else
<h class="centered"> Access denied </h>
@endif

<script>
    const addclass = 'color';
    const $cols = $('.files_table_element').click(function (e) {
        $cols.removeClass(addclass);
        $(this).addClass(addclass);
    });
</script>

</body>
</html>

