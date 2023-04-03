@extends('layouts.master')

@isset($category)
    @section('title', 'Категория ' . $category->name)
@endisset


@section('content')
    <h1>
        {{$category->name}} {{ $category->products->count() }} шт.
    </h1>
    <p>
        {{ $category->description }}
    </p>
    <div class="row">
        @foreach($category->products as $product)
            @include('layouts.card', compact('product'))
        @endforeach
    </div>
@endsection
