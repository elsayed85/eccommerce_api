<?php

namespace App\Http\Controllers\Api;

use App\category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\categoryCollection;
use App\Http\Resources\Api\categoryResource;
use Illuminate\Http\Request;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return categoryResource::collection(category::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        return new categoryResource($category);
    }
}
