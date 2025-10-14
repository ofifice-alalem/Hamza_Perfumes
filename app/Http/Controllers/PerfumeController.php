<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use App\Models\Category;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    public function index()
    {
        $perfumes = Perfume::with(['category', 'perfumePrices'])
            ->withCount('sales')
            ->paginate(15);
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
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث العطر بنجاح']);
        }
        
        return redirect()->route('perfumes.index')->with('success', 'تم تحديث العطر بنجاح');
    }

    public function destroy(Perfume $perfume)
    {
        $perfume->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'تم حذف العطر بنجاح']);
        }
        
        return redirect()->route('perfumes.index')->with('success', 'تم حذف العطر بنجاح');
    }

    public function checkUnique(Request $request)
    {
        $name = trim((string) $request->get('name'));
        if ($name === '') {
            return response()->json(['exists' => false]);
        }
        $exists = Perfume::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->exists();
        return response()->json(['exists' => $exists]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->get('q'));
        if ($query === '') {
            return response()->json(['results' => []]);
        }
        
        $perfumes = Perfume::with('category')
            ->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($query) . '%'])
            ->limit(10)
            ->get()
            ->map(function ($perfume) {
                return [
                    'id' => $perfume->id,
                    'name' => $perfume->name,
                    'category' => $perfume->category ? $perfume->category->name : 'غير محدد',
                    'url' => route('sales.index') . '?perfume_id=' . $perfume->id
                ];
            });
            
        return response()->json(['results' => $perfumes]);
    }

    public function searchUncategorized(Request $request)
    {
        $query = trim((string) $request->get('q'));
        if ($query === '') {
            return response()->json(['results' => []]);
        }
        
        $perfumes = Perfume::whereNull('category_id')
            ->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($query) . '%'])
            ->limit(10)
            ->get()
            ->map(function ($perfume) {
                return [
                    'id' => $perfume->id,
                    'name' => $perfume->name,
                    'category' => 'غير مصنف',
                    'url' => route('sales.index') . '?perfume_id=' . $perfume->id
                ];
            });
            
        return response()->json(['results' => $perfumes]);
    }
    
    public function getPerfumeById($id)
    {
        $perfume = Perfume::with('category')->find($id);
        
        if (!$perfume) {
            return response()->json(null, 404);
        }
        
        return response()->json([
            'id' => $perfume->id,
            'name' => $perfume->name,
            'category' => $perfume->category ? $perfume->category->name : 'غير محدد'
        ]);
    }
}
