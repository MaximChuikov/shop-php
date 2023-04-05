<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MailSender;
use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orders()->paginate(10);
        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('auth.orders.show', compact('order'));
    }
    public function ahead(Request $request)
    {
        $order = Order::find($request->orderId);
        $order->status += 1;
        $order->save();
        $customer = User::find($order->user_id);
        MailSender::statusChanged($customer, $order);
        session()->flash('success', 'Статус заказа обновлен! Клиенту отправлено письмо о смене статуса заказа.');

        return redirect()->route('home');
    }
    public function received(Request $request)
    {
        $order = Order::find($request->orderId);
        $order->status += 1;
        $order->save();
        session()->flash('success', 'Вы получили заказ, заказывайте еще!');

        return redirect()->route('person.orders.index');
    }
}
