<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by Category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->latest()->paginate(9); // 3x3 grid or just 3 as per readme? Readme says "Three product cards are visible", implies a row of 3? I'll do 9 for a full grid.
        $categories = Category::withCount('products')->get();
        $totalProducts = $products->total();

        return view('home', compact('products', 'categories', 'totalProducts'));
    }
}
