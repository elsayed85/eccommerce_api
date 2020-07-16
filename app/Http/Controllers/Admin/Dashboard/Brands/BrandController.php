<?php

namespace App\Http\Controllers\Admin\Dashboard\Brands;

use App\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Brand::all();
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
     * @param  int  Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand  $brand)
    {
        return $brand;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand  $brand)
    {
        return $brand;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand  $brand)
    {
        return $brand;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand  $brand)
    {
        return $brand;
    }
}
