<?php

namespace App;

use App\Notifications\VerifyApiEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Airlock\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens, SoftDeletes;
    protected $softDelete = true;


    // constants
    const PASSMIN = 6;
    const PASSMAX = 30;
    const NAMEMIN = 6;
    const NAMEMAX = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'email_verified_at' , 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    public function sendApiEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail); // my notification
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }
    public function wishlist()
    {
        return $this->hasOne(wishlist::class, 'user_id');
    }
    public function review()
    {
        return $this->hasMany(Review::class);
    }
    public function hasCart($cart_id)
    {
        if ($this->cart->id == $cart_id) {
            return true;
        }
        return false;
    }
    public function hasWishlist($wishlist_id)
    {
        if ($this->wishlist->id == $wishlist_id) {
            return true;
        }
        return false;
    }
    public function isBuyProduct($id)
    {
        if ($this->order->count() == 0) {
            return false;
        }
        return collect($this->order->map(function ($order) use ($id) {
            return $order->item->map(function ($item) use ($id) {
                if ($item->product_id != $id) {
                    return ['state' => false];
                }
                return ['state' => true];
            });
        }))->collapse()->collapse()->get('state');
    }
}
