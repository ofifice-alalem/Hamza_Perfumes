<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use App\Models\Category;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    public function index()
    {
        $perfumes = Perfume::all();
        return view('perfumes.index', compact('perfumes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('perfumes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id'
        ]);
        Perfume::create($request->only('name', 'category_id'));
        return redirect()->route('perfumes.index')->with('success', 'تم إضافة العطر بنجاح');
    }

    public function edit(Perfume $perfume)
    {
        $categories = Category::all();
        return view('perfumes.edit', compact('perfume', 'categories'));
    }

    public function update(Request $request, Perfume $perfume)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id'
        ]);
        $perfume->update($request->only('name', 'category_id'));
        return redirect()->route('perfumes.index')->with('success', 'تم تحديث العطر بنجاح');
    }

    public function destroy(Perfume $perfume)
    {
        $perfume->delete();
        return redirect()->route('perfumes.index')->with('success', 'تم حذف العطر بنجاح');
    }
}
