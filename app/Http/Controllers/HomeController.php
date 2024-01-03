<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Shoe;
use App\Models\Cart;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Shoe::all();
        $carts = Cart::all();
        $total = Cart::sum(DB::raw('price * quantity'));
        $totalRounded = round($total, 2);
        $ids = Cart::pluck('id')->toArray();
        return view('index', compact('products', 'carts', 'totalRounded', 'ids'));
    }
    public function add(Request $request)
    {
        $id = $request->input('id');
        // Tìm sản phẩm theo ID
        $shoe = Shoe::find($id);
        if ($shoe) {
            $data = [
                'id' => $id,
                'name' => $shoe->name,
                'price' => $shoe->price,
                "image" => $shoe->image,
                "color" => $shoe->color,
                "quantity" => 1
            ];
            DB::table('carts')->insert($data);
            $total = Cart::sum(DB::raw('price * quantity'));
            $totalRounded = round($total, 2);
            return response()->json(['data' => $data, '$totalRounded' => $totalRounded]);
        } else {

            return response()->json(['error' => 'Product not found'], 404);
        }

    }

    public function updateCart(Request $request)
    {
        $id = $request->input('id');
        $quantity = $request->input('quantity');

        DB::table('carts')
            ->where('id', $id)
            ->update(['quantity' => $quantity]);
        $total = Cart::sum(DB::raw('price * quantity'));
        $totalRounded = round($total, 2);
        return response()->json($totalRounded);
    }
    public function deleteCart(Request $request)
    {
        $check_empty = 0;
        $id = $request->id;
        Cart::where('id', $id)->delete();
        $total = Cart::sum(DB::raw('price * quantity'));
        $totalRounded = round($total, 2);
        $count = Cart::count();
        if ($count == 0) {
            $check_empty = 1;
        }
        return response()->json(['$check_empty' => $check_empty, '$totalRounded' => $totalRounded]);
    }


}