<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\CartItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CartItemCollection;
use App\Http\Resources\Api\CartItemResource;
use App\Order;
use App\products;
use Dotenv\Regex\Success;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Store a newly created Cart in storage and return the data to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = request()->user();
        if ($user->cart()->count() >= 1) {
            return responder()->success()->meta([
                'message' => 'you already have a cart',
                'cart_id' => $user->cart->id
            ]);
        }
        $cart = Cart::create([
            'id' => md5(uniqid(rand(), true)),
            'user_id' => $user->id,
        ]);

        return response()->json([
            'Message' => 'A new cart have been created for you!',
            'cartToken' => $cart->id,
        ], 201);
    }

    /**
     * Display the specified Cart.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart, Request $request)
    {
        if (!request()->user()->hasCart($cart->id)) {
            return  responder()->error(404, 'this cart is not found for this user');
        }
        return response()->json([
            'cart' => $cart->id,
            'Items' => new CartItemCollection($cart->items),
        ], 200);
    }

    /**
     * Adds Products to the given Cart;
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return void
     */
    public function addProduct(Cart $cart, Request $request)
    {
        if (!request()->user()->hasCart($cart->id)) {
            return  responder()->error(404, 'this cart is not found for this user');
        }
        $this->validate($request, [
            'productID' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $productID = $request->input('productID');
        $quantity = $request->input('quantity');

        $product =  products::find($productID);
        $product_quantity =  $product->quantity;

        if ($product_quantity  == 0) {
            return responder()->success()->meta([
                'message' => 'this product is empty',
                'avaiable' => $product_quantity
            ]);
        }
        if (!$product->canBuy($quantity)) {
            return responder()->success()->meta([
                'message' => 'product quantity must be less than '  . $product_quantity,
                'avaiable' => $product_quantity
            ]);
        }
        //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
        $cartItem = CartItem::where(['cart_id' => $cart->getKey(), 'product_id' => $productID])->first();

        if ($cartItem) {
            $cartItem->quantity = $quantity;
            CartItem::where(['cart_id' => $cart->getKey(), 'product_id' => $productID])->update(['quantity' => $quantity]);
        } else {
            CartItem::create(['cart_id' => $cart->getKey(), 'product_id' => $productID, 'quantity' => $quantity]);
        }

        return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);
    }



    /**
     * checkout the cart Items and create and order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return void
     */
    public function checkout(Cart $cart, Request $request)
    {
        if (!request()->user()->hasCart($cart->id)) {
            return  responder()->error(404, 'this cart is not found for this user');
        }
        $items = $cart->items;

        // cart is empty
        if ($items->count() <= 0) {
            return response()->json(['message' => 'cart is empty']);
        }
return responder()->success()->
        // empty cart & not delete -> task
        $this->validate($request, [
            'adress' => 'required|min:3',
            'credit_card_num' => 'required|numeric',
            'expiration_year' => 'required',
            'expiration_month' => 'required',
            'cvc' => 'required|numeric',
            'name' => 'required|min:3'
        ]);

        $user = request()->user();
        $userID = $user->id;


        // user data + payment data
        $name = request()->name;
        $adress = $request->input('adress');
        $creditCardNumber = $request->input('credit_card_num');

        $TotalPrice = (float) 0.0;



        foreach ($items as $item) {
            // item => [quantity , cart_id , product_id]

            $product = products::find($item->product_id);

            // if item is deleted
            ////////////////// ........................ ////////////////////////////

            $price = $product->PriceAfterDiscount;

            if ($product->avaiable) {


                

                // discount *********************************************************




                // total price
                $TotalPrice = $TotalPrice + ($price * $item->quantity);

                // in stock
                $product->type->quantity = $product->type->quantity - $item->quantity;

                // in product
                $product->quantity = $product->quantity - $item->quantity;

                // update product quantity
                $product->save();

                // update type quantity
                $product->type->save();
            } else {
                return response()->json([
                    'message' => 'The quantity you\'re ordering of [' . $product->name .
                        '] isn\'t available in stock, only [' . $product->quantity . '] units are in Stock, please update your cart to proceed',
                ], 400);
            }
        }
        /**
         * Credit Card information should be sent to a payment gateway for processing and validation,
         * the response should be dealt with here, but since this is a dummy project we'll
         * just assume that the information is sent and the payment process was done succefully,
         */

        $PaymentGatewayResponse = products::PAYMENT;
        $transactionID = md5(uniqid(rand(), true));
        if ($PaymentGatewayResponse) {
            $order = Order::create([
                'totalPrice' => $TotalPrice,
                'name' => $name,
                'address' => $adress,
                'user_id' => isset($userID) ? $userID : null,
                'transactionID' => $transactionID,
            ]);

            $items->map(function ($item) use ($order) {
                $order->item()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity
                ]);
            });

            // delete all items in cart
            $cart->items->map(function ($item) {
                CartItem::where('product_id', $item->product_id)->where('cart_id', $item->cart_id)->delete();
            });

            // $cart->delete();

            return response()->json([
                'message' => 'you\'re order has been completed succefully, thanks for shopping with us!',
                'orderID' => $order->getKey(),
            ], 200);
        } else {
            // delete all items in cart
            $cart->items->map(function ($item) {
                CartItem::where('product_id', $item->product_id)->where('cart_id', $item->cart_id)->delete();
            });
            // $cart->delete();
        }
        return response()->json(['message' => 'done']);
    }


    public function removeProduct(Cart $cart)
    {
        if (!request()->user()->hasCart($cart->id)) {
            return  responder()->error(404, 'this cart is not found for this user');
        }
        $this->validate(request(), [
            'product_id' => 'required|exists:products,id'
        ]);

        $product = products::find(request()->product_id);
        $productInCart =  $cart->items->where('product_id',  $product->id)->first();
        if (is_null($productInCart)) {
            return responder()->error(404, 'this product is not found in this cart');
        }
        $productInCart->delete();
        return responder()->success()->meta(['message' => 'deleted succfully', 'deleted' => true]);
    }


    public function clear(Cart $cart)
    {
        if ($cart->items->count() <= 0) {
            return response()->json(['message' => 'cart is empty']);
        }
        $cart->items->map(function ($item) {
            $item->delete();
        });
        return responder()->success()->meta(['message' => 'cleared succfully'])->respond();
    }
}
