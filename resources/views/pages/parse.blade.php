@extends('layouts.default')

@section('content')

@if (request()->ip() == '192.168.0.15')

<div class="wrap">
    <div class="content">
        <div  class="content_left">
            <div  class="content_top">
                <form action="#" method="get">
                        <div class="top_search_form">
                            <h2> Выгрузка приборов по категориям </h2>

                            <label for="from"> От </label>
                            <input type="text" id="from" name="from" placeholder="От" value="{{ $request['company_name'] ?? '' }}" required>

                            <label for="until"> До </label>
                            <input type="text" id="until" name="until" placeholder="До" value="{{ $request['device_name'] ?? '' }}" required>

                        </div>
                        <div class="submit_button">
                            <label> ъуъ </label>
                            <input id="submit" type="submit" value="Поиск">
                        </div>
                </form>

                <form action="#" method="get">
                    <div class="top_search_form">
                        <h2> Выгрузка прибора по id </h2>

                        <label> ъуъ </label>
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
            <div class="button" onclick="window.location.href='/trans'">
                <span> Начать выгрузку </span>
            </div>
            <div class="button" onclick="window.location.href='/updateStorage'">
                <span> Обновить склад </span>
            </div>
        </div>
    </div>
</div>
@else
<h class="centered"> Access denied </h>
@endif

<script>

    $( document ).ready(function(){
        document.getElementById('admin').className += ' active';
    })
</script>

@stop

