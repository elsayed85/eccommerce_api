<?php

namespace App\Http\Controllers\Api;

use App\CartItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WishlistItemCollection;
use App\products;
use App\wishlist;
use App\wishlistItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WishlistController extends Controller
{

    public function store(Request $request)
    {
        $user = request()->user();
        if ($user->wishlist()->count() >= 1) {
            return responder()->success()->meta([
                'message' => 'you already have a wishlist',
                'wishlist_id' => $user->wishlist->id
            ]);
        }
        $wishlist = wishlist::create([
            'id' => md5(uniqid(rand(), true)),
            'user_id' => $user->id,
        ]);

        return response()->json([
            'Message' => 'A new wishlist have been created for you!',
            'wishlistToken' => $wishlist->id,
        ], 201);
    }

    /**
     * Display the specified wishlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(wishlist $wishlist, Request $request)
    {
        if (!request()->user()->hasWishlist($wishlist->id)) {
            return  responder()->error(404, 'this wishlist is not found for this user');
        }
        return response()->json([
            'wishlist' => $wishlist->id,
            'Items' => new WishlistItemCollection($wishlist->items),
        ], 200);
    }

    /**
     * Adds Products to the given Cart;
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return void
     */
    public function addProduct(wishlist $wishlist, Request $request)
    {
        if (!request()->user()->hasWishlist($wishlist->id)) {
            return  responder()->error(404, 'this wishlist is not found for this user');
        }
        $this->validate($request, [
            'productID' => 'required|exists:products,id',
        ]);

        $productID = $request->input('productID');

        $product =  products::find($productID);
        $product_quantity =  $product->quantity;

        if ($product_quantity  == 0) {
            return responder()->success()->meta([
                'message' => 'this product is empty',
                'avaiable' => $product_quantity
            ]);
        }

        //check if the the same product is already in the wishlist, if true update the quantity, if not create a new one.
        $wishlistItem = wishlistItems::where(['wishlist_id' => $wishlist->getKey(), 'product_id' => $productID])->first();
        if (is_null($wishlistItem)) {
            wishlistItems::create(['wishlist_id' => $wishlist->getKey(), 'product_id' => $productID]);
        } else {
            return response()->json(['message' => 'already in the wishlist', 'code' => 'already-exist'], 200);
        }
        return response()->json(['message' => 'succfully aded to wishlist', 'code' => 'add-successfully'], 200);
    }



    public function removeProduct(wishlist $wishlist)
    {
        if (!request()->user()->hasWishlist($wishlist->id)) {
            return  responder()->error(404, 'this wishlist is not found for this user');
        }
        $this->validate(request(), [
            'product_id' => 'required|exists:products,id'
        ]);

        $product = products::find(request()->product_id);
        $productInWishlist =  $wishlist->items->where('product_id',  $product->id)->first();
        if (is_null($productInWishlist)) {
            return responder()->error(404, 'this product is not found in this wishlist');
        }
        $productInWishlist->delete();
        return responder()->success()->meta(['message' => 'deleted succfully', 'deleted' => true]);
    }


    public function clear(wishlist $wishlist)
    {
        if (!request()->user()->hasWishlist($wishlist->id)) {
            return  responder()->error(404, 'this wishlist is not found for this user');
        }
        if ($wishlist->items->count() <= 0) {
            return response()->json(['message' => 'wishlist is empty']);
        }
        $wishlist->items->map(function ($item) {
            $item->delete();
        });
        return responder()->success()->meta(['message' => 'cleared succfully'])->respond();
    }

    public function moveToCart(wishlist $wishlist)
    {
        // user has this wishlist
        if (!request()->user()->hasWishlist($wishlist->id)) {
            return  responder()->error(404, 'this wishlist is not found for this user');
        }
        // cart is empty
        if ($wishlist->items->count() <= 0) {
            return response()->json(['message' => 'wishlist is empty']);
        }
        // product exist
        $this->validate(request(), [
            'product_id' => 'required|exists:products,id'
        ]);
        // find product in wishlist
        $productInWishlist = wishlistItems::where('wishlist_id', $wishlist->id)
            ->where('product_id', request()->product_id)->first();

        if (is_null($productInWishlist)) {
            return \response()->json(['message' => 'this product is not exist in the wishlist'], 404);
        }

        $user = request()->user();
        $cart = $user->cart;
        // create cart if not exist
        if (is_null($cart)) {
            $cart->create(['id' => md5(uniqid(rand(), true))]);
        }
        // check if item in the cart
        $productInCart = CartItem::where('cart_id', $cart->id)
            ->where('product_id', request()->product_id)->first();
        // if in the cart return message
        if (!is_null($productInCart)) {
            return \response()->json([
                'message' => 'product already in the cart'
            ]);
        }
        CartItem::create(['cart_id' => $cart->getKey(), 'product_id' => $productInWishlist->product_id, 'quantity' => 1]);
        // remove item from the wishlist
        $productInWishlist->delete();
        return \response()->json([
            'message' => 'moved to cart succfully'
        ]);
    }
}
