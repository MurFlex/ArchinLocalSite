@extends('layouts.default')

@section('content')
    <div class="wrap">
        <div class="content">
            <div class="main_content">
                <div class="container shadow min-vh-100 py-2">
                    <form>
                        <div class="mb-3">
                            <label for="registry" class="form-label"> <b> Регистрационный номер типа СИ </b> </label>
                            <input type="text" name="registry" class="form-control" id="registry" placeholder="Регистрационный номер">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label"> <b> Наименование СИ </b> </label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Наименование СИ">
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label"> <b> Тип СИ </b> </label>
                            <input type="text" name="type" class="form-control" id="type" placeholder="Тип СИ">
                        </div>
                        <div class="mb-3">
                            <label for="modification" class="form-label"> <b> Модификация СИ </b> </label>
                            <input type="text" name="modification" class="form-control" id="modification" placeholder="Модификация СИ">
                        </div>
                        <button type="submit" class="btn btn-primary"> Запрос </button>
                    </form>
                </div>
            </div>

            <div>
                <button style="max-width: 10%" type="submit" class="btn btn-primary mb-3" onclick="window.location.href = '/'"> Назад </button>
            </div>
        </div>
        <div class="btn-up btn-up_hide"> &#9650; </div>
    </div>

@stop
