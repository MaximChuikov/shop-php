@extends('auth.layouts.master')

@section('title', 'Добавить продавца')

@section('content')
    <div class="col-md-12">
        <h1>Сделать пользователя продавцом</h1>

        <form method="POST" enctype="multipart/form-data" action="{{ route('roles.add') }}">
            <div>
                @csrf
                <br>
                <br>
                <div class="input-group row">
                    <label for="email" class="col-sm-2 col-form-label">Почта:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="email" id="email" />
                    </div>
                </div>
                <br>
                <br>
                <button class="btn btn-success" type="submit">Сохранить</button>
            </div>
        </form>

        <h3>Продавцы</h3>

        <table class="table mt-5">
            <tbody>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Имя продавца
                </th>
                <th>
                    Почта
                </th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <form method="POST" enctype="multipart/form-data" action="{{ route('roles.delete') }}">
                                <div>
                                    @csrf
                                    <input type="hidden" name="email" id="email" value="{{ $user->email }}" />
                                    <button class="btn btn-warning" type="submit">Удалить</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection

