@extends('layouts.default')

@section('content')

@if (request()->ip() == '192.168.0.15')


    <div class="d-flex flex-column justify-content-between">
        <div class="d-flex">
            <form class="mt-3" style="margin-right: 30px">
                <h3>Выгрузка в бд по индексам json</h3>
                <div class="row mb-3">
                    <label for="from" class="col-sm-2 col-form-label"> От </label>
                    <div class="col-sm-10">
                        <input type="text" id="from" name="from" placeholder="От" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="until" class="col-sm-2 col-form-label"> До </label>
                    <div class="col-sm-10">
                        <input type="text" id="until" name="until" placeholder="До" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"> Выгрузить </button>
            </form>

            <form class="mt-3">
                <h3>Выгрузка в бд по индексам id</h3>
                <div class="row mb-3">
                    <label for="from" class="col-sm-2 col-form-label"> От </label>
                    <div class="col-sm-10">
                        <input type="text" id="search_id" name="id" placeholder="id прибора" class="form-control">
                    </div>
                </div>
                <div class="row mb-3" style="visibility: hidden">
                    <label for="until" class="col-sm-2 col-form-label"> До </label>
                    <div class="col-sm-10">
                        <input type="text" id="until" name="until" placeholder="До" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"> Выгрузить </button>
            </form>
            <div class="d-flex flex-column ml-2">
                <p class="mb-1"> Last id is: </p>
                <p> 222518538 </p>
            </div>
        </div>
        <div class="d-flex flex-column w-25 sticky-bottom" style="bottom:10px">
            <button class="btn btn-primary mt-2" onclick="window.location.href='/trans'"> Начать выгрузку </button>
            <button class="btn btn-primary mt-2" onclick="window.location.href='/updateStorage'"> Обновить склад </button>
            <button class="btn btn-primary mt-2" onclick="history.back();"> Назад </button>
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

