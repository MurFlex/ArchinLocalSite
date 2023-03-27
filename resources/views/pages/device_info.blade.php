@extends('layouts.default')

@section('content')

<div class="wrap">
    <div class="content">
        <div class="content_top">
            <h1 style="margin-bottom: 10px"> Прибор №{{ $device['device_id'] }} </h1>
        </div>
        <div class="main_content">
            <table style="margin-right: 20px" class="table table-striped table-bordered table-sm mt-2">
                <thead>
                <tr style="font-weight: bold;">
                    <td width="40%">Наименование</td>
                    <td>Результат</td>
                </tr>
                </thead>
                <tbody>
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
                                @if($index == 'signPass' || $index == 'signMi')
                                    {{ $param == 1 ? 'Да' : 'Нет' }}
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
                </tbody>
            </table>
        </div>
        <div>
            <button style="max-width: 10%" type="submit" class="btn btn-primary" onclick="history.back()"> Назад </button>
        </div>
    </div>

</div>

@stop
