@extends('layouts.default')

@section('content')
    <div class="wrap mt-2">
        <form class="d-flex flex-column mb-2" action="#" method="get">
            <div class="searching_form_top d-flex mb-2">
                <div style="margin-right: 2%; min-width: 32%;" class="form-group">
                    <label for="cname">Название компании</label>
                    <input type="text" class="form-control" id="cname" name="company_name" value="{{ $request['company_name'] ?? '' }}" placeholder="Введите название компании">
                </div>
                <div style="margin-right: 2%; min-width: 32%;" class="form-group">
                    <label for="dname">Название прибора</label>
                    <input type="text" class="form-control" id="dname" name="device_name" value="{{ $request['device_name'] ?? '' }}" placeholder="Введите название прибора">
                </div>
                <div style="margin-right: 2%; min-width: 32%;" class="form-group mb-1">
                    <label for="year-select">Год поверки:</label>
                    <select name="years" id="year-select" class="form-select" aria-label="Default select example">
                        <option value="">Выбрать год</option>
                        @for($i = $year; $i >= 2022; $i--)
                            <option @if($request['years'] == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <button style="max-width: 10%" type="submit" class="btn btn-primary"> Поиск </button>
        </form>
        <div class="top_table_content d-flex justify-content-between">
            <h4>Найдено: <span id="found-count"></span></h4>
            @if($bannedCompanies !== null)
                <a data-bs-toggle="modal" href="#exampleModalToggle" role="button"> <h6>Скрытые компании</h6></a>
            @endif
        </div>
        <table style="margin-right: 20px" class="table table-striped table-bordered table-sm">
            <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Имя компании</th>
                <th class="text-center" scope="col">Кол-во</th>
                <th class="text-center" scope="col">Не прошло</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($companies as $id => $company)
                <tr id="{{ $id }}">
                    <th width="10%" scope="row">{{ $id }}</th>
                    <td width="60%">{{ $company['name'] }}</td>
                    <td class="text-center" width="10%"> {{ $company['count'] ?? 'Нет данных' }}</td>
                    <td class="text-center" width="10%"> {{ $company['inapplicable'] ?? 'Нет данных' }}</td>
                    <td height="100%" class="d-flex">
                        <button style="margin-right: 5px" type="button" data-bs-toggle="modal" href="#editing_form" class="btn edit-button btn-outline-primary btn-small">Ред.</button>
                        <button onclick="window.location = '/company/'.concat($(this).closest('tr').attr('id'));" style="margin-right: 5px" type="button" class="btn btn-outline-info btn-small">Инф.</button>
                        <button type="button" class="btn btn-outline-danger btn-small" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setCookie('id', $(this).closest('tr').attr('id'), 1)">Удл.</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Удаление компании с id <span id="modal-id"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button style="min-width: 18%" type="button" id="delete-button" class="btn btn-danger">Да</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Скрытые компании</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="location.reload()"></button>
                </div>
                <div class="modal-body">
                    @if($bannedCompanies !== null)
                        @foreach($bannedCompanies as $id => $company)
                            <div id="{{ $id }}" class="banned_company d-flex justify-content-between mb-1">
                                <p class="company_name"> {{ $company }} </p>
                                <a href="#" class="deleting_button"> &#10005; </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editing_form" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel"> Редактирование компании </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="d-flex flex-column" action="#" method="get">
                        <div class="searching_form_top mb-2">
                            <div style="margin-right: 2%" class="form-group">
                                <label class="mb-1" for="cname">Старое название компании</label>
                                <input disabled type="text" class="form-control mb-3" id="edit-from-cname" name="company_name" value="">
                            </div>
                            <div style="margin-right: 2%" class="form-group mb-1">
                                <label class="mb-1" for="dname">Новое название компании</label>
                                <input type="text" class="form-control" id="edit-to-cname" name="edit-to-cname" placeholder="Введите новое название">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="replace-on" id="replace-on">
                                <label class="form-check-label" for="flexCheckDefault">
                                    С заменой (если существует)
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="edit-submit" type="button" class="btn accept-edit btn-primary" data-bs-dismiss="modal"> Принять </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let editing_id;
        let editing_name;

        $(document).ready(function () {
            $("#edit-submit").click(function (event) {
                var renameData = {
                    id: editing_id,
                    rename_from: $("#edit-from-cname").val(),
                    rename_to: $("#edit-to-cname").val(),
                    apply: $('#replace-on').is(":checked"),
                };

                $.ajax({
                    type: "POST",
                    url: '/api/rename/',
                    data: renameData,
                    dataType: "json",
                    encode: true,
                    success: function(response)
                    {
                        if(response['response'] === 'found') {
                            alert('Компания с таким именем уже существует.');
                        } else if (response['response'] === 'success') {
                            alert('Успешно!');
                        }
                    }
                });

                event.preventDefault();
            });
        });

        $('.edit-button').click(function () {
            editing_name = $(this).parent().parent().find("td:eq(0)").text();
            editing_id = $(this).parent().parent().attr('id');

            $('#edit-from-cname').val(editing_name);
        });

        // todo make an inspection if response was bad
        $('.banned_company').ready(function () {
            $('.deleting_button').click(function () {
                let deleted_id = $(this).parent().attr('id');
                let unbanData = {
                    'id':$(this).parent().attr('id'),
                };
                $.ajax({
                    type: 'POST',
                    url: '/api/return/',
                    data: unbanData,
                    dataType: "json",
                    encode: true,
                    success: $(this).text('\u{2713}')
                });
                return false;
            })
        });


        $('#delete-button').click(function () {
            let deleteData = {
                id: $('#modal-id').text(),
            };
            $.ajax({
                type: 'POST',
                url: '/api/delete/',
                data: deleteData,
                dataType: "json",
                encode: true,
                success: location.reload()
            });
            return false;
        });

        $('.btn').click(function () {
            $('#modal-id').text($(this).closest('tr').attr('id'));
        });

        $(document).ready(function() {
            $('#found-count').text($('tbody tr').length);
            setCookie('searching_history', $(location).attr('href'));
            document.getElementById('main').className += ' active';
        });

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
    </script>
@stop
