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
            background-color: #125ea8;
            color: white;
        }

        .footer {
            height: 6em;
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
            padding: 12px 20px;
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
            display: flex;
            justify-content: space-between;
            min-height: 10vh;
            min-width: 98%;
            border-radius: 5px;

        }

        .table_element-1 {
            background-color: white;
            border: 1px solid black;
            margin-top: 5px;
            /*margin: 5px auto;*/
            padding: 10px;
            cursor: pointer;
        }

        .header_elements, .footer_elements {
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            width: 80%;
            /*font-size: 2vh;*/
            font-size: 20px;
            font-weight: bold;
        }

        .header_elements:first-child {
            margin-top: 0;
            margin-bottom: 20px;
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
            padding-top: 30px;
            text-decoration: none;
        }

        .nav_item:first-child {
            padding-top: 20px;
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

        img {
            max-height: 50px;
            max-width: 100%;
            width: auto;
        }

        .edit_table_element {
            padding: 10px;
            border: 1px solid black;
            border-radius: 5px;
            margin-bottom: 5px;
        }

    </style>

</head>
<body>

<div class="header">
    <div class="header_elements">
        <div class="nav_item" style="display: block"> <a href="http://192.168.0.15/dev"> <img src="{{ asset('storage/1.png') }}" alt='ООО "ПРИБОРЭЛЕКТРО"' width="812" height="140"> </a> </div>
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
                @if(!$found)
                    <h2 class="table_header"> Результаты поиска по компании: <span class="request_name"> {{ $request['company_name'] }} </span></h2>
                @else
                    <h2 class="table_header"> Результатов по запросу <span class="request_name"> "{{ $request['company_name'] }}" </span> не найдено, выполнен поиск по <span class="request_name">"{{ $found }}"</span></h2>
                @endif
            @else
                <h2 class="table_header"> Список компаний </h2>
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
{{--                <script>window.location = "/dev";</script>--}}
            @elseif(!empty($request) and empty($companies))
                <h2 class="table_header"> Ничего не найдено. </h2>
            @else
                @if($request['edit_mode'] !== 'True')
                    @if($storages !== null)
                        @foreach ($storages as $id => $storage)
                            <div id="{{ $id }}" class="table_element-1"> {{ $companies[$id] !== '' ? $companies[$id] : '-' }} <b> Количество: {{ $storage }}, не прошло: {{ $inapplicable[$id] }} </b> </div>
                        @endforeach
                    @else
                        @foreach ($companies as $id => $company)
                            <div id="{{ $id }}" class="table_element-1"> {{ $company }} </div>
                        @endforeach
                    @endif
                @else
                    <form action="#">
                    @foreach ($companies as $id => $company)
                        <div id="{{ $id }}" class="edit_table_element">
                            <input type="checkbox" id="element-{{ $id }}" name="{{ $id }}">
                            <label for="element-{{ $id }}"> {{ $company }} </label>
                        </div>
                    @endforeach
                        <div class="submit_button">
                            <input type="submit" value="Принять">
                        </div>
                    </form>
                @endif
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
                    <input type="text" id="dname" name="device_name" placeholder="Название прибора" value="{{ $request['device_name'] ?? '' }}">

                    <label for="year-select">Год поверки:</label>
                    <select name="years" id="year-select">
                        <option value="">Не работает</option>
                        @for($i = $year; $i >= 2022; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @if (request()->ip() == '192.168.0.15') <label for="edit_mode">Edit mode</label>
                    <input type="checkbox" id="edit_mode" name="edit_mode" value="True" @if($request['edit_mode'] == 'True') checked @endif/>
                    @endif
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
    function getQueryVariable(variable)
    {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++)
        {
            var pair = vars[i].split("=");
            if (pair[0] === variable)
            {
                return pair[1];
            }
        }
        return -1; //not found
    }

    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    $(document).ready(function() {
        setCookie('searching_history', $(location).attr('href'));
        if($('#dname').attr('value').length > 0) {
            document.getElementById("cname").disabled = true;
            $('#cname').attr('placeholder', 'Недоступен');
        }
    });

    url = 'http://192.168.0.15/company/';

    $(".table_element-1").dblclick(function(event) {
        window.location.href = url.concat($(event.target).closest('div').text().substr(0, $(event.target).text().indexOf('Количество')-1));
    });

    dname.onchange = function () {
        if (this.value != "" || this.value.length > 0) {
            document.getElementById("cname").disabled = true;
            $('#cname').attr('placeholder', 'Недоступен');
        } else {
            document.getElementById('cname').disabled = false;
            $('#cname').attr('placeholder', 'Название компании');
        }
    }

</script>

</body>
</html>
