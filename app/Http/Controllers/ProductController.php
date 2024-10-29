<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\DB;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
class ProductController extends Controller
{
    //
    public function index()
    {

        // $brands = Location::paginate(10);
        $brands = DB::table('product')->paginate(10);
        return response()->json($brands, 200);
    }
    public function store(Request $request)
    {

        $pd= new Product();
        $pd->name=$request->name;
        $pd->soluong=$request->soluong;
        $pd->save();
        return response()->json([
            'status' => true,
            'message' => ' created successfully',
        ]);
    }
}
