<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Archin </title>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
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

        .table {
            border-right: 2px solid black;
            height: 100%;
            min-width: 80%;
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
            /*margin: 8px 0;*/
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #125ee0;
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
            width: 10%;
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
            margin: 20px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .main_content {
            max-height: 50vh;
        }

        .content_top {
            max-height: 55vh;
            overflow: auto
        }

        .red {
            background-color: red;
            color: white;
        }

        td {
            border: 2px solid black;
            padding: 10px;
        }

        table {
            border-collapse: collapse;
            border: 2px solid rgb(200, 200, 200);
            letter-spacing: 1px;
        }

        .table_element {

        }

        .pager {
            width: 30%;
            display: flex;
        }

        .table_top {
            display: flex;
            justify-content: space-between;
        }

        .page_button {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .active {
            background-color: darkblue;
        }
    </style>

</head>
<body>

<div class="header">
    <div class="header_elements">
        <div class="nav_item" onclick="window.location.replace(getCookie('searching_history'))"> Логотип  </div>
        <div class="nav_item"> <a onclick="window.location.replace(getCookie('searching_history'))"> На главную </a> </div>
        @if (request()->ip() == '192.168.0.15') <div class="nav_item"> <a href="http://192.168.0.15/parse"> Admin </a> </div> @endif
        <div class="nav_item">  </div>
        <div class="nav_item">  </div>
        <div class="nav_item">  </div>
    </div>
</div>

<div class="wrap">
    <div class="content">
        <div class="main_content">
            <div class="table_top">
                <h1 style="margin-bottom: 10px;"> {{ $name }} </h1>
            </div>
            <div class="content_top">
                <table id="myTable" style="width: 100%" class="content_table">
                    <tr style="font-weight: bold;">
                        <td>Название категории</td>
                        <td style="width: 20%">Тип СИ</td>
                        <td style="width: 20%">СИ всего</td>
                        <td style="width: 20%">СИ непригодно</td>
                    </tr>
                    @foreach ($categories as $id => $category)
                        <tr id="{{ $id }}">
                            <td>{{ $category}}</td>
                            <td>{{ trim($category_types[$id]) !== '' ? $category_types[$id] : 'Нет данных' }}</td>
                            <td>{{ $applicable[$id] + $inapplicable[$id] }}</td>
                            <td>{{ $inapplicable[$id] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="content_bottom">
            <button class="product_bottom_buttons" type="button" onclick="window.location.replace(getCookie('searching_history'))"> Назад </button>
        </div>
    </div>

</div>



<div class="footer">

</div>

<script>
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)===' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    $(document).ready(function () {
        $('#list > li').click(function (event) {
            $(this).children("ul").slideToggle();
            event.stopPropagation();
        });
    });

    url = 'http://192.168.0.15/company/';

    url = (url.concat($('h1').text())).concat('/');

    $('tr').not(':first').dblclick(function(){
        window.location.href = url.concat($(this).closest('tr').attr('id'));
    });
</script>

</body>
</html>