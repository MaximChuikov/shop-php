<p>Уважаемый {{ $name }}</p>

<p>У вас заказали товар на сумму {{ $fullSum }} создан</p>

<p>Соберите и отправьте его как можно быстрее. Не забывайте менять статус заказа в панели продавца</p>

<table>
    <tbody>
    @foreach($order->products as $product)
        <tr>
            <td>
                <a href="{{ route('product', [$product->category->code, $product->code]) }}">
                    <img height="56px" src="{{ Storage::url($product->image) }}">
                    {{ $product->name }}
                </a>
            </td>
            <td>
                {{ $product->pivot->count }} шт.
            </td>
            <td>{{ $product->price }} руб.</td>
            <td>{{ $product->getPriceForCount() }} руб.</td>
        </tr>
    @endforeach
    </tbody>
</table>
