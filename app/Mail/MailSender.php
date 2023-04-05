<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailSender
{
    public static function customerOrderCreation(User $user, Order $order) {
        Mail::to($user->email)->send(new OrderCreated($user->name, $order));
    }
    public static function sellerOrderCreation(User $seller, Order $order) {
        //TODO заменить здесь строчку $sellers = User::where('is_admin', 1); на принятие в параметрах массива продавцов
        Mail::to($seller->email)->send(new OrderCreatedForSeller($seller->name, $order));
    }
    public static function statusChanged(User $user, Order $order) {
        Mail::to($user->email)->send(new StatusChanged($user->name, $order));
    }
}
