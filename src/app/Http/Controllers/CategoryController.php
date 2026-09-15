<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;

class CategoryController extends Controller
{
        public function subcategories(int $category)
    {
        return Subcategory::where('category_id', $category)->get();
    }
}
