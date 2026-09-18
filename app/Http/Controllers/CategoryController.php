<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Sekarang kodenya jadi jauh lebih pendek
        $categories = Category::withCount('items')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
         return view('categories.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'division_pj' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'division_pj.required' => 'The division pj field is required.',
        ]);

        // Simpan ke database
        Category::create([
            'name' => $request->name,
            'division_pj' => $request->division_pj,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division_pj' => 'required',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function show(Category $category)
    {
        // Load relasi items agar bisa ditampilkan di halaman detail
        $category->load('items'); 
        return view('categories.show', compact('category'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted!');
    }
}