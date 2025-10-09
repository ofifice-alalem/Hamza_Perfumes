<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryPrice;
use App\Models\Size;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('perfumes')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function edit(Category $category)
    {
        $sizes = Size::all();
        return view('categories.edit', compact('category', 'sizes'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sizes' => 'required|array',
            'sizes.*.price_regular' => 'nullable|numeric|min:0',
            'sizes.*.price_vip' => 'nullable|numeric|min:0'
        ]);

        $category->update(['name' => $request->name]);

        foreach ($request->sizes as $sizeId => $priceData) {
            if (!empty($priceData['price_regular']) && !empty($priceData['price_vip'])) {
                CategoryPrice::updateOrCreate(
                    ['category_id' => $category->id, 'size_id' => $sizeId],
                    ['price_regular' => $priceData['price_regular'], 'price_vip' => $priceData['price_vip']]
                );
            } else {
                CategoryPrice::where('category_id', $category->id)->where('size_id', $sizeId)->delete();
            }
        }

        return redirect()->route('categories.index')->with('success', 'تم تحديث التصنيف والأسعار بنجاح');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }
}
