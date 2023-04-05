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
    </div>
@endsection

