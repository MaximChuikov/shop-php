<?php

namespace App\Models;

use App\Mail\OrderCreated;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    protected $fillable = ['address'];
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('count')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', '>=', 1);
    }

    public function statusText()
    {
        $statuses = [
            'В корзине',
            'Заказ принят',
            'Заказ собирается',
            'Заказ отправлен в доставку продавцом',
            'Заказ доставлен'
        ];
        return $statuses[$this->status];
    }

    public function scopeOrders($query)
    {
        $isAdmin = Auth::user()->isAdmin();
        $statusMax = $isAdmin ? 3 : 4;
        return $isAdmin
            ? (
            $query->where('status', '<=', $statusMax)
                ->where('status', '>=', 1)
            ) : (
            $query->where('user_id', Auth::user()->id)->where('status', '<=', $statusMax)
                ->where('status', '>=', 1)
            );
    }

    public function calculateFullSum()
    {
        $sum = 0;
        foreach ($this->products as $product) {
            $sum += $product->getPriceForCount();
        }
        return $sum;
    }

    public static function eraseOrderSum()
    {
        session()->forget('full_order_sum');
    }

    public static function changeFullSum($changeSum)
    {
        $sum = self::getFullSum() + $changeSum;
        session(['full_order_sum' => $sum]);
    }

    public static function getFullSum()
    {
        return session('full_order_sum', 0);
    }

    public function saveOrder($name, $phone, $address, $email)
    {
        if ($this->status == 0) {
            $this->name = $name;
            $this->phone = $phone;
            $this->address = $address;
            $this->status = 1;
            $this->save();
            session()->forget('orderId');
            Mail::to($email)->send(new OrderCreated($name, $this));
            // TODO: send to my email
            return true;
        } else {
            return false;
        }
    }
}
