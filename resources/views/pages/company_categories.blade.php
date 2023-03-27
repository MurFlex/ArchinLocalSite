@extends('layouts.default')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-2 mb-2">
            <li class="breadcrumb-item" onclick="window.location.replace(getCookie('searching_history'))"><a href="#">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $name }}</li>
        </ol>
    </nav>

<div class="wrap">
    <div class="content">
        <div class="top_table d-flex justify-content-between">
            <h3 style="margin-bottom: 10px;"> {{ $name }} </h3>
            <div class="form-group">
                <input type="text" class="form-control pull-right" id="search" placeholder="Поиск по таблице">
            </div>
        </div>
            <table id="mytable" style="margin-right: 20px" class="table table-striped table-bordered table-sm mt-2">
                <thead>
                <tr style="font-weight: bold;">
                    <td>Название категории</td>
                    <td width="20%">Тип СИ</td>
                    <td width="20%">СИ всего ({{ $count }})</td>
                    <td width="20%">СИ непригодно ({{ $inapplicableCount }})</td>
                    <td width="1%"></td>
                </tr>
                </thead>
                <tbody>
                @if(isset($categories))
                    @foreach ($categories as $id => $category)
                        <tr id="{{ $id }}">
                            <td>{{ $category['category_title']}}</td>
                            <td>{{ trim($category['category_type']) !== '' ? $category['category_type'] : 'Нет данных' }}</td>
                            <td>{{ $category['count'] }}</td>
                            <td>{{ $category['inapplicable'] }}</td>
                            <td> <button onclick="window.location = '/company/{{ $company_id }}/'.concat($(this).closest('tr').attr('id'));" type="button" class="btn btn-outline-primary btn-small"> &#8594; </button> </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        <div>
            <button style="max-width: 10%" type="submit" class="btn btn-primary mb-3" onclick="window.location.replace(getCookie('searching_history'))"> Назад </button>
        </div>

</div>

<script>
    $(document).ready(function(){
        $("#search").keyup(function(){
            var _this = this;

            function escapeHtml(text, lang) {
                if(lang === 'ru') {
                    var map = {
                        'C':'С',
                        'K':'К',
                        'M':'М',
                        'P':'Р',
                        'H':'Н',
                        'E':'Е',
                        'A':'А',
                        'X':'Х',
                        'B':'В',
                        'T':'Т',
                    };

                    return text.replace(/[CKMPHEAXBT]/g, function(m) { return map[m]; });
                } else if(lang === 'en') {
                    var map = {
                        'С':'C',
                        'К':'K',
                        'М':'M',
                        'Р':'P',
                        'Н':'H',
                        'Е':'E',
                        'А':'A',
                        'Х':'X',
                        'В':'B',
                        'Т':'T',
                    };

                    return text.replace(/[СКМРНЕАХВТ]/g, function(m) { return map[m]; });
                } else {
                    return 'ban request';
                }
            }

            $.each($("#mytable tbody tr"), function() {
                if ($(this).text().toLowerCase().indexOf(escapeHtml($(_this).val(), 'ru').toLowerCase()) !== -1 || $(this).text().toLowerCase().indexOf(escapeHtml($(_this).val(), 'en').toLowerCase()) !== -1) {
                    $(this).show();
                } else if($(_this).val() === '') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>

@stop

