@extends('layouts.default')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-2 mb-2">
            <li class="breadcrumb-item" onclick="window.location.replace(getCookie('searching_history'))"><a href="#">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page" onclick="window.location.href = '/company/{{ $company_id }}'"> <a href="#"> {{ $name }} </a></li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $category_name }}</li>
        </ol>
    </nav>

<div class="wrap">
    <div class="content">
        <div class="main_content">
            <table style="margin-right: 20px" class="table table-striped table-bordered table-sm mt-2">
                <thead>
                <tr style="font-weight: bold;">
                    <td width="5%">id</td>
                    <td width="5%">СИ пригодно</td>
                    <td>Название типа СИ</td>
                    <td>Тип СИ</td>
                    <td>Модификация</td>
                    <td>Заводской номер</td>
                    <td>Дата поверки</td>
                    <td>Действительна до</td>
                    <td>Год выпуска СИ</td>
                    <td>Тип поверки</td>
                </tr>
                </thead>
                <tbody>
                @if(isset($devices))
                    @foreach ($devices as $device)
                        <tr id="{{ $device['device_id'] }}" style="text-align: center" @if($device['applicable'] == 'N') class="bg-warning" @endif>
                            <td>{{ $device['device_id'] }}</td>
                            <td>{{ $device['applicable'] == 'Y' ? 'Да' : 'Нет' }}</td>
                            <td>{{ $category['category_title'] }}</td>
                            <td>{{ $category['category_type'] }}</td>
                            <td>{{ $device['modification'] }}</td>
                            <td>{{ $device['manufactureNum'] }}</td>
                            <td>{{ $device['vrfDate'] }}</td>
                            <td>{{ $device['validDate'] }}  @if(isset($device['validDate']) && strtotime($device['validDate']) < time()) <br> <span class="text-danger"> expired </span> @endif</td>
                            <td>{{ isset($device['manufactureYear']) ? $device['manufactureYear'] : 'Нет данных' }}</td>
                            <td>{{ $device['vriType'] }}</td>
                            <td> <button onclick="window.location = '/device/'.concat($(this).closest('tr').attr('id'));" type="button" class="btn  @if($device['applicable'] == 'N') btn-dark @else btn-outline-primary @endif btn-small"> &#8594; </button></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div>
            <button style="max-width: 10%" type="submit" class="btn btn-primary mb-3" onclick="window.location.href = '/company/{{ $company_id }}'"> Назад </button>
        </div>
    </div>
    <div class="btn-up btn-up_hide"> &#9650; </div>
</div>
@stop
