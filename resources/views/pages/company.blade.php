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
            <div class="container shadow min-vh-100 py-2">
                <div class="table-responsive">
                    <table style="text-align: center" id="devices-table" class="table align-middle accordion">
                        <thead>
                        <tr>
                            <th style="width: 5%" scope="col"><span>id</span></th>
                            <th style="width: 5%"><span>СИ пригодно</span></th>
                            <th><span>Название типа СИ</span></th>
                            <th><span>Тип СИ</span></th>
                            <th><span>Модификация</span></th>
                            <th><span>Заводской номер</span></th>
                            <th><span>Дата поверки</span></th>
                            <th><span>Действительна до</span></th>
                            <th><span>Год выпуска</span></th>
                            <th><span>Тип поверки</span></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                @if(count($device) > 1)
                                    <tr  @if($device[0]['applicable'] == 'N') class="bg-warning" @endif id="{{ $device[0]['device_id'] }}" data-bs-toggle="collapse" data-bs-target="#r{{ $device[0]['device_id'] }}">
                                        <td scope="row">
                                            <span>{{ $device[0]['device_id'] }}</span>
                                            <svg width="25px" height="25px" class="arrow-bottom-5" viewBox="0 0 154 109">
                                                <symbol id="Arrow" viewBox="-73.9 -55.3 135.9 85.4">
                                                    <g>
                                                        <polygon points="-73.9,29.1 -6,-9.2 61.9,30.1 61.9,14.2 -6,-25.1 -73.9,14.2 "/>
                                                    </g>
                                                    <g>
                                                        <polygon points="-73.9,-1 -6,-39.3 61.9,0 61.9,-16 -6,-55.3 -73.9,-16 "/>
                                                    </g>
                                                </symbol>
                                                <use xlink:href="#Arrow" width="135.9" height="85.4" id="XMLID_1_" x="-73.9" y="-55.3" transform="matrix(1.007 0 0 -1.007 83.0005 42)" />
                                            </svg></td>
                                        <td>{{ $device[0]['applicable'] == 'Y' ? 'Да' : 'Нет' }}</td>
                                        <td>{{ $category['category_title'] }}</td>
                                        <td>{{ $category['category_type'] }}</td>
                                        <td>{{ $device[0]['modification'] }}</td>
                                        <td>{{ $device[0]['manufactureNum'] ?? 'Нет данных' }}</td>
                                        <td>{{ $device[0]['vrfDate'] }}</td>
                                        <td>{{ $device[0]['validDate']}}  @if(isset($device[0]['validDate']) && strtotime($device[0]['validDate']) < time()) <br> <span class="text-danger"> expired </span> @endif</td>
                                        <td>{{ isset($device[0]['manufactureYear']) ? $device[0]['manufactureYear'] : 'Нет данных' }}</td>
                                        <td>{{ $device[0]['vriType'] }}</td>
                                        <td> <button onclick="window.location = '/device/'.concat($(this).closest('tr').attr('id'));" type="button" class="btn  @if($device[0]['applicable'] == 'N') btn-dark @else btn-outline-primary @endif btn-small"> &#8594; </button></td>
                                    </tr>
                                    <tr class="collapse accordion-collapse" id="r{{ $device[0]['device_id'] }}" data-bs-parent=".table">
                                    @for($i = 1; $i < count($device); $i++)
                                        <tr class="collapse accordion-collapse bg-secondary text-light" id="r{{ $device[0]['device_id'] }}" data-bs-parent=".table">
                                            <td scope="row">{{ $device[$i]['device_id'] }}</td>
                                            <td>{{ $device[$i]['applicable'] == 'Y' ? 'Да' : 'Нет' }}</td>
                                            <td>{{ $category['category_title'] }}</td>
                                            <td>{{ $category['category_type'] }}</td>
                                            <td>{{ $device[$i]['modification'] }}</td>
                                            <td>{{ $device[$i]['manufactureNum'] ?? 'Нет данных' }}</td>
                                            <td>{{ $device[$i]['vrfDate'] }}</td>
                                            <td>{{ $device[$i]['validDate'] }}  @if(isset($device[$i]['validDate'])) <br> <span class="text-danger"> expired </span> @endif</td>
                                            <td>{{ isset($device[$i]['manufactureYear']) ? $device[$i]['manufactureYear'] : 'Нет данных' }}</td>
                                            <td>{{ $device[$i]['vriType'] }}</td>
                                            <td> <button onclick="window.location = '/device/'.concat($(this).closest('tr').children('td').eq(0).text());" type="button" class="btn btn-dark  btn-small"> &#8594; </button></td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr id="{{ $device[0]['device_id'] }}" style="text-align: center" @if($device[0]['applicable'] == 'N') class="bg-warning" @endif data-bs-toggle="collapse" data-bs-target="#r1">
                                        <td scope="row">{{ $device[0]['device_id'] }}</td>
                                        <td>{{ $device[0]['applicable'] == 'Y' ? 'Да' : 'Нет' }}</td>
                                        <td>{{ $category['category_title'] }}</td>
                                        <td>{{ $category['category_type'] }}</td>
                                        <td>{{ $device[0]['modification'] }}</td>
                                        <td>{{ $device[0]['manufactureNum'] ?? 'Нет данных' }}</td>
                                        <td>{{ $device[0]['vrfDate'] }}</td>
                                        <td>{{ $device[0]['validDate'] }}  @if(isset($device[0]['validDate']) && strtotime($device[0]['validDate']) < time()) <br> <span class="text-danger"> expired </span> @endif</td>
                                        <td>{{ isset($device[0]['manufactureYear']) ? $device[0]['manufactureYear'] : 'Нет данных' }}</td>
                                        <td>{{ $device[0]['vriType'] }}</td>
                                        <td> <button onclick="window.location = '/device/'.concat($(this).closest('tr').attr('id'));" type="button" class="btn  @if($device[0]['applicable'] == 'N') btn-dark @else btn-outline-primary @endif btn-small"> &#8594; </button></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    let table = $('#devices-table');
                    let rows = table.find('tr:gt(0)').toArray().sort(comparer(0));
                    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
                    function comparer(index) {
                        return function(a, b) {
                            var valA = Number(getCellValue(a, index).replace('r', '')), valB = Number(getCellValue(b, index).replace('r', ''));
                            return $.isNumeric(valA) && $.isNumeric(valB) ? valB - valA : valA.toString().localeCompare(valB)
                        }
                    }
                    function getCellValue(row, index){ return $(row).eq(index).attr('id')}
                });
            </script>
        </div>
        <div>
            <button style="max-width: 10%; margin-top: 10px" type="submit" class="btn btn-primary mb-3" onclick="window.location.href = '/company/{{ $company_id }}'"> Назад </button>
        </div>
    </div>
    <div class="btn-up btn-up_hide"> &#9650; </div>
</div>

@stop
