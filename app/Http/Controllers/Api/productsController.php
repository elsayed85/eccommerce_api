<?php

namespace App\Http\Controllers\Api;

use App\Ads;
use App\category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AdsResource;
use App\Http\Resources\Api\ProductCollection;
use App\Http\Resources\Api\productResoucre;
use App\Http\Resources\Api\typeCollection;
use App\products;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  productResoucre::collection(products::paginate(10));
        // $products = products::with(['specification', 'brand:id,name', 'images' , 'type:id,name'])->get();
        // return responder()->success($products)->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(products $product)
    {
        return  new productResoucre($product);
    }

    public function search()
    {
        $products = QueryBuilder::for(products::class)
            ->allowedFilters(['trsnalations.name'])
            ->get();
        return productResoucre::collection($products);
    }
    public function filter()
    {
        $products = QueryBuilder::for(products::class)
            ->allowedFilters(
                [
                    AllowedFilter::exact('type.category_id', null),
                    AllowedFilter::exact('brand_id'),
                    AllowedFilter::exact('type_id'),
                    AllowedFilter::scope('price')
                ]
            )
            ->defaultSort('price')
            ->allowedSorts('price', 'discount', 'id')
            ->get();
        return new ProductCollection($products);
    }

    // pages //
    public function home()
    {

        $ads = AdsResource::collection(Ads::all(['id', 'path']));

        // Random
        $random['name'] = 'Random';
        $random['src'] = productResoucre::collection(products::inRandomOrder()->take(5)->get());

        $data['sections'][] = $random;

        // categories
        // get 7 random category
        $categories = category::all()->random(7);
        foreach ($categories as $category) {
            // category types > 0
            if (!$category->type->isEmpty()) {
                // get 1 random category type
                $type = $category->type->random();
                // category type has at least one product
                if (!$type->product->isEmpty()) {
                    // category type 5 random products
                    $data['sections'][$category->id]['title'] = $category->name;
                    $data['sections'][$category->id]['src'] = new ProductCollection($type->product->take(5));
                }
            }
        }
        return ['ads' => $ads, 'sections' => collect($data['sections'])->values()];
    }
}
