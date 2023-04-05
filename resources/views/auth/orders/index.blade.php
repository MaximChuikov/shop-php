@extends('auth.layouts.master')

@section('title', 'Заказы')

@section('content')
    <div class="col-md-12">
        <h1>Заказы</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Имя
                </th>
                <th>
                    Телефон
                </th>
                <th>
                    Адрес
                </th>
                <th>
                    Когда отправлен
                </th>
                <th>
                    Сумма
                </th>
                <th>
                    Статус
                </th>
                <th>
                    Действия
                </th>
            </tr>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id}}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->address }}</td>
                    <td>{{ $order->created_at->format('H:i d/m/Y') }}</td>
                    <td>{{ $order->calculateFullSum() }} руб.</td>
                    <td>{{ $order->statusText() }}</td>
                    <td class="d-inline-flex" style="gap: 8px">
                        <div class="btn-group" role="group">
                            <a class="btn btn-success" type="button"
                                @admin href="{{ route('orders.show', $order) }}"
                                @else href="{{ route('person.orders.show', $order) }}"
                                @endadmin>Открыть</a>
                        </div>
                        @seller
                        <form method="POST" action="{{ route('orders.ahead') }}">
                            @csrf
                            <input type="hidden" name="orderId" value="{{ $order->id }}">
                            <input type="submit" class="btn btn-warning" value="Продвинуть статус"/>
                        </form>
                        @else
                            @if($order->status == 4)
                                <form method="POST" action="{{ route('person.orders.setReceived') }}">
                                    @csrf
                                    <input type="hidden" name="orderId" value="{{ $order->id }}">
                                    <input type="submit" class="btn btn-warning" value="Заказ получен"/>
                                </form>
                            @endif
                        @endseller

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
@endsection
