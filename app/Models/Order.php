<?php

namespace App\Models;

use App\Mail\MailSender;
use App\Mail\OrderCreated;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    protected $fillable = ['address', 'seller_id', 'user_id', 'phone', 'status', 'name'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('count')->withTimestamps();
    }
    public function productCount(Product $product) {
        return $this->products()->where('product_id', $product->id)->sum('count');
    }
    public function seller()
    {
        return $this->hasOne(User::class, 'id', 'seller_id');
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
        //Get only sellers orders
        if (Auth::user()->isSeller()) {
            return $query->where('seller_id', Auth::user()->id)->where('status', '<=', 3)
                ->where('status', '>=', 1);
        }
        //Get
        else {
            return $query->where('user_id', Auth::user()->id)->where('status', '<=', 4)
                ->where('status', '>=', 0);
        }
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

    public function saveOrder($name, $phone, $address, User $user)
    {
        if ($this->status == 0) {
            $orderProducts = $this->products;
            $productsBySeller = $orderProducts->groupBy('seller_id');
            foreach ($productsBySeller as $seller => $sellerProducts) {
                $sellerOrder = new Order([
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'user_id' => $user->id,
                    'seller_id' => $seller,
                    'status' => 1,
                ]);
                $sellerOrder->save();

                $pivotValues = $sellerProducts->pluck('pivot.count', 'id')->toArray();

                $pivotValues2 = collect($pivotValues)->mapWithKeys(function($count, $id) {
                    return [$id => ['count' => $count]];
                })->toArray();
                $sellerOrder->products()->sync($pivotValues2);
                MailSender::sellerOrderCreation(User::find($seller), $sellerOrder);
            }
            session()->forget('orderId');
            MailSender::customerOrderCreation($user, $this);
            $this->delete();
            return true;
        } else {
            return false;
        }
    }
}
