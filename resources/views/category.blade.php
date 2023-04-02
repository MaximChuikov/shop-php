@extends('master')

@isset($category)
    @section('title', 'Категория ' . $category->name)
@endisset


@section('content')
    <div class="starter-template">
        <h1>
            {{$category->name}} {{ $category->products->count() }} шт.
        </h1>
        <p>
            {{ $category->description }}
        </p>
        <div class="row">
            @foreach($category->products as $product)
                @include('card', compact('product'))
            @endforeach
        </div>
    </div>
@endsection
