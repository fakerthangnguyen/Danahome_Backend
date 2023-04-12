<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Room;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index()
    {
        // All Categories
        $categories = Categories::all();
        return response()->json([
            'categories' => $categories
        ],200);
    }


    public function create(Request $request)
    {
        try {
        Categories::create([
            'name'  => $request->name,
            'slug'  => $request->slug,
            'parent_id'  => $request->parent_id,
        ]);
        return response()->json([
            'message' => 'Tạo chuyên mục thành công'
        ],200);

    } catch(\Exception $e){
        // Trả về 1 JSon Response
        return response()->json([
            'message' => 'Có điều gì đó không đúng !'
        ],500);
       }
    }

    public function store(Request $request)
    {

    }


    public function show(string $id)
    {

    }


    public function edit(string $id)
    {

    }


    public function update(Request $request, string $id)
    {

    }


    public function destroy(string $id)
    {
        //
    }
}
