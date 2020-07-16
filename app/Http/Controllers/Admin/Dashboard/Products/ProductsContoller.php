<?php

namespace App\Http\Controllers\Admin\Dashboard\Products;

use App\Http\Controllers\Controller;
use App\products;
use Illuminate\Http\Request;

class ProductsContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return products::with('brand')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  products $product
     * @return \Illuminate\Http\Response
     */
    public function show(products $product)
    {
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  products $product
     * @return \Illuminate\Http\Response
     */
    public function edit(products $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  products $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  products $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(products $product)
    {
        //
    }
}
