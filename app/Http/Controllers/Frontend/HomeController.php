<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'featured_products' => Product::with('category')->where('status', true)->inRandomOrder()->take(4)->get(),
            'new_arrivals' => Product::with('category')->where('status', true)->latest()->take(4)->get(),
            'categories' => Category::take(4)->get(),
        ]);
    }
}
