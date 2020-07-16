<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ReviewCollection;
use App\Http\Resources\Api\ReviewResource;
use App\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index()
    {
        return new ReviewCollection(request()->user()->review);
    }
    public function store()
    {
        $this->validate(request(), [
            'product_id' => 'required|exists:products,id',
            'review' => 'required|min:4',
            'rate' => 'required|in:1,2,3,4,5'
        ]);
        $product = request()->product_id;
        if (!request()->user()->isBuyProduct($product)) {
            return responder()->error(404, 'you can not review this product , buy it first');
        }
        $user = request()->user();
        if ($user->review->where('product_id', $product)->count() >= 1) {
            return responder()->error(404, 'you already added review on this product');
        }
        $review = $user->review()->create([
            'product_id' => $product,
            'review' => request()->review,
            'rate' => request()->rate
        ]);
        return ['data' => new ReviewResource($review), 'message' => 'created succfully'];
    }
    public function remove(Review $review)
    {
        if ($review->user_id != request()->user()->id) {
            return responder()->error(404, 'you can not delete this review');
        }
        $review->delete();
        return response()->json(['message' => 'deleted succfully']);
    }
}
