<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class ProductsController extends Controller
{
    function index() {

        $products = DB::table('sanpham')->get()->toArray();
        // dd($products);
        $page = 10;

        $currentPage = request()->get('page', 1);

        $panigator = new LengthAwarePaginator(
            array_slice($products, ($currentPage - 1) * $page, $page),
            count($products),
            $page,
            $currentPage,
            ['path' => request()->url()]
        );
        // $data = DB::table('products')->paginate(10);
        return view('pages.guest.products', ['products' => $panigator, 'title_search' => null]);
    }


    function product_detail($productId) {
        $product = DB::table('sanpham')->where('SP_Ma', $productId)->first();
        // dd($product);
        return view('pages.guest.product-detail', compact('product'));
    }


    function search(Request $request) {
        // dd($request);
        $searchTerm = $request->input('key');
        // $results=[];
        // $products =  [
        //     (object)[
        //         'id' => 1,
        //         'name' => 'Áo Vest đen bóng',
        //         'price' => 4790000,
        //         'image' => '/storage/images/products/vest-2.jpg',
        //     ]
        // ];
        $results = DB::table('sanpham')
                        ->where('SP_Ten', 'like', '%' . $searchTerm . '%')
                        ->get()->toArray();
        $page = 10;

        $currentPage = request()->get('page', 1);

        $panigator = new LengthAwarePaginator(
            array_slice($results, ($currentPage - 1) * $page, $page),
            count($results),
            $page,
            $currentPage,
            ['path' => request()->url()]
        );
        
        return view('pages.guest.products', ['products' => $panigator, 'title_search' => 'Kết quả tìm kiếm']);
    }
}