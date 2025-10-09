<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return view('sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate(['label' => 'required|string|max:255']);
        Size::create($request->only('label'));
        return redirect()->route('sizes.index')->with('success', 'تم إضافة الحجم بنجاح');
    }

    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate(['label' => 'required|string|max:255']);
        $size->update($request->only('label'));
        return redirect()->route('sizes.index')->with('success', 'تم تحديث الحجم بنجاح');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('sizes.index')->with('success', 'تم حذف الحجم بنجاح');
    }
}
