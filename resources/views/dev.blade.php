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
            padding: 10px;
            /*margin: 8px 0;*/
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #125ee0;
        }

        .table_top {
            max-width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            /*margin-right: 10px;*/
        }

        .table_header {
            height: 100%;
            padding: 1.5vh;
            font-size: 1.75vh;
        }

        .table_content {
            max-height: 89.5%;
            display: flex;
            flex-direction: column;
            overflow: auto
        }

        .table_element-1 {
            min-height: 10vh;
            min-width: 98%;
            border-radius: 5px;
        }

        .table_element-1 {
            background-color: white;
            border: 1px solid black;
            margin: 5px auto;
            padding: 10px;
            cursor: pointer;
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

        .pager {
            max-width: 30%;
            display: flex;
        }

        .page_button {
            text-align: center;
            max-width: 50px;
            margin-top: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .active {
            background-color: darkblue !important;
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
    <div class="table">
        <div class="table_top">
            @if (isset($request['company_name']))
                <h2 class="table_header"> Результаты поиска по компании: <span class="request_name"> {{ $request['company_name'] }} </span></h2>
            @else
                <h2 class="table_header"> Список компаний </h2>
{{--                <form action="#" method="get" class="pager">--}}
{{--<!--                    --><?php //dd($max_page) ?>--}}
{{--                    @if($max_page < 5 and $max_page >= 2)--}}
{{--                        @for($i = 1; $i <= $max_page; $i++)--}}
{{--                            <input class="@if($i == $page) active @endif page_button" name='page' type="submit" value="{{ $i }}">--}}
{{--                        @endfor--}}
{{--                    @elseif($max_page == 1)--}}
{{--                    @else--}}
{{--                        @if($page == 1)--}}
{{--                            @for($i = $page; $i < $page + 5; $i++)--}}
{{--                                <input class="page_button @if($i == $page) active @endif" name='page' type="submit" value="{{ $i }}">--}}
{{--                            @endfor--}}
{{--                            <input class="page_button" name='page' type="submit" value="{{ $max_page }}">--}}
{{--                        @elseif($page == 2)--}}
{{--                            @for($i = $page; $i < $page + 5; $i++)--}}
{{--                                <input class="page_button @if($i == $page+1) active @endif" name='page' type="submit" value="{{ $i-1 }}">--}}
{{--                            @endfor--}}
{{--                            <input class="page_button" name='page' type="submit" value="{{ $max_page }}">--}}
{{--                        @elseif($page == $max_page-1)--}}
{{--                                <input class="page_button" name='page' type="submit" value="<<">--}}
{{--                            @for($i = $page-3; $i <= $max_page; $i++)--}}
{{--                                <input class="page_button @if($i == $max_page) active @endif" name='page' type="submit" value="{{ $i-1 }}">--}}
{{--                            @endfor--}}
{{--                            <input class="page_button" name='page' type="submit" value="{{ $max_page }}">--}}
{{--                        @elseif($page == $max_page)--}}
{{--                            <input class="page_button" name='page' type="submit" value="<<">--}}
{{--                            @for($i = $page-4; $i <= $max_page+1; $i++)--}}
{{--                                <input class="page_button @if($i == $max_page+1) active @endif" name='page' type="submit" value="{{ $i-1 }}">--}}
{{--                            @endfor--}}
{{--                        @else--}}
{{--                            @if($page > 3)--}}
{{--                                <input class="page_button" name='page' type="submit" value="<<">--}}
{{--                            @endif--}}
{{--                            @for($i = $page; $i < $page + 5; $i++)--}}
{{--                                <input class="page_button @if($i == $page+2) active @endif" name='page' type="submit" value="{{ $i-2 }}">--}}
{{--                            @endfor--}}
{{--                            <input class="page_button" name='page' type="submit" value="{{ $max_page }}">--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                </form>--}}
            @endif
        </div>
        <div class="table_content">
            @if(isset($results))
                @foreach ($results as $id => $item)
                <div id="{{ $id }}" class="table_element-1">
                    {{ $item }}
                </div>
                @endforeach
            @elseif(isset($request) && (!isset($request['company_name']) && !isset($request['device_name'])))
                <script>window.location = "/dev";</script>
            @elseif(!empty($request) and empty($companies))
                <h2 class="table_header"> Ничего не найдено. </h2>
            @else
                @foreach ($companies as $id => $company)
                    <div id="{{ $id }}" class="table_element-1"> {{ $company }} </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="search">
        <form style="height: 100%;" action="#" method="get">
            <div class="search_form">
                <div class="top_search_form">
                    <label for="cname">Название компании</label>
                    <input type="text" id="cname" name="company_name" placeholder="Название компании" value="{{ $request['company_name'] ?? '' }}">

                    <label for="dname">Название прибора</label>
                    <input disabled type="text" id="dname" name="device_name" placeholder="Название прибора" value="{{ $request['device_name'] ?? '' }}">

                    <label for="add_options">Дополнительные опции</label>
                    <select disabled id="add_options" name="add_options">
                        <option value="option 1">option 1</option>
                        <option value="option 2">option 2</option>
                        <option value="option 3">option 3</option>
                    </select>
                </div>
                <div class="submit_button">
                    <input type="submit" value="Поиск">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="footer">
{{--    <div class="footer_elements">--}}
{{--        <div class="footer_element">Тел. </div>--}}
{{--        <div class="footer_element">Адрес </div>--}}
{{--        <div class="footer_element">Инфо </div>--}}
{{--        <div class="footer_element">Инфо</div>--}}
{{--    </div>--}}
</div>


<script>
    url = 'http://192.168.0.15/company/';

    $(".table_element-1").dblclick(function(event){
        window.location.href = url.concat($(event.target).text())
    });

</script>

</body>
</html>
