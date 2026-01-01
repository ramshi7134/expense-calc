<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $categories = \App\Models\Category::where(function($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $validated['user_id'] = $user->id;
        \App\Models\Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        $category = \App\Models\Category::where('user_id', $user->id)->findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $category = \App\Models\Category::where('user_id', $user->id)->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'limit' => 'nullable|numeric|min:0',
        ]);
        $category->update(['name' => $validated['name']]);
        
        // Update or create budget for current month
        if (!empty($validated['limit'])) {
            \App\Models\Budget::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'month' => now()->month,
                    'year' => now()->year,
                ],
                ['limit' => $validated['limit']]
            );
        }
        
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $category = \App\Models\Category::where('user_id', $user->id)->findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
