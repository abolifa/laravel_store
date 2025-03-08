<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Section;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;

class HomeDataController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('parent', 'children')->get();
        $brands = Brand::with('products')->get();
        $sliders = Slider::all();
        $offers = Offer::with('brand', 'category')->get();
        $sections = Section::all();

        return response()->json([
            'categories' => $categories,
            'brands' => $brands,
            'sliders' => $sliders,
            'offers' => $offers,
            'sections' => $sections,
        ]);
    }
}
