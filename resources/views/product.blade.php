@extends('master')

@section('title', 'Товар')

@section('content')
    <div class="starter-template">
        <h1>{{ $product->name }}</h1>
        <p>Цена: <b>{{ $product->price }} руб.</b></p>
        <img src="{{ $product->image }}">
        <p>{{ $product->description }}</p>
        <a class="btn btn-success" href="#">Добавить в корзину</a>
    </div>
@endsection
