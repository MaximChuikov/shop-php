<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orders()->paginate(10);
        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
//        dd($order->statusText());
        return view('auth.orders.show', compact('order'));
    }
    public function ahead(Request $request)
    {
        $order = Order::find($request->orderId);
        $order->status += 1;
        $order->save();
        //TODO отправка email клиенту о смене статуса заказа
        $user = $order->user_id;

        // Mail::send;
        session()->flash('success', 'Статус заказа обновлен! Клиенту отправлено письмо о смене статуса заказа.');

        return redirect()->route('home');
    }
    public function received(Request $request)
    {
        $order = Order::find($request->orderId);
        $order->status += 1;
        $order->save();
        //TODO отправка email клиенту и продавцу о получении заказа
        $user = $order->user_id;

        // Mail::send;
        session()->flash('success', 'Вы получили заказ, заказывайте еще!');

        return redirect()->route('person.orders.index');
    }
}
