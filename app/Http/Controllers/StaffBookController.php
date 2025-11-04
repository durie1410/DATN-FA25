<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class StaffBookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->paginate(10);
        return view('staff.books.index', compact('books'));
    }

    public function show($id)
    {
        $book = Book::with(['category', 'borrows', 'reviews'])->findOrFail($id);
        return view('staff.books.show', compact('book'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('staff.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $request->validate([
            'ten_sach' => 'required|string|max:255',
            'tac_gia' => 'required|string|max:255',
            'nam_xuat_ban' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'mo_ta' => 'nullable|string',
        ]);

        $book->update($request->all());

        return redirect()->route('staff.books.index')
            ->with('success', 'Cập nhật sách thành công!');
    }
}

