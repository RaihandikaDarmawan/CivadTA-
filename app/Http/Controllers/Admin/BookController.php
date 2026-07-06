<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $query = Book::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        return view('admin.manajemen_buku', [
            'dummyBooks' => $query->get()
        ]);
    }

    public function store(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:255',
            'category' => 'required|string|min:3|max:255',
            'class_level' => 'required|integer|min:1',
            'price' => 'required|string|min:3',
            'stock' => 'required|integer|min:0',
            'desc' => 'required|string|min:10',
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.min' => 'Judul buku minimal harus 3 karakter.',
            'author.required' => 'Penulis buku wajib diisi.',
            'author.min' => 'Penulis buku minimal harus 3 karakter.',
            'category.required' => 'Kategori wajib diisi.',
            'category.min' => 'Kategori minimal harus 3 karakter.',
            'class_level.required' => 'Tingkat kelas wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'price.min' => 'Harga buku minimal harus 3 digit.',
            'stock.required' => 'Stok buku wajib diisi.',
            'stock.min' => 'Stok buku tidak boleh kurang dari 0.',
            'desc.required' => 'Deskripsi buku wajib diisi.',
            'desc.min' => 'Deskripsi buku minimal harus 10 karakter.',
        ]);

        $basePrice = (int) str_replace('.', '', $request->input('price'));
        Book::create([
            'title' => $request->input('title'),
            'author' => $request->input('author') ?? 'Admin',
            'category' => $request->input('category'),
            'class' => 'Kelas ' . $request->input('class_level'),
            'base_price' => $basePrice,
            'price' => 'Rp ' . number_format($basePrice, 0, ',', '.'),
            'stock' => (int) $request->input('stock'),
            'image' => $request->input('image') ?? 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=800&auto=format&fit=crop',
            'desc' => $request->input('desc')
        ]);
        
        return redirect()->back()->with('success', 'Buku baru berhasil ditambahkan ke database!');
    }

    public function update(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $request->validate([
            'id' => 'required|exists:books,id',
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:255',
            'category' => 'required|string|min:3|max:255',
            'class_level' => 'required|integer|min:1',
            'price' => 'required|string|min:3',
            'stock' => 'required|integer|min:0',
            'desc' => 'required|string|min:10',
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.min' => 'Judul buku minimal harus 3 karakter.',
            'author.required' => 'Penulis buku wajib diisi.',
            'author.min' => 'Penulis buku minimal harus 3 karakter.',
            'category.required' => 'Kategori wajib diisi.',
            'category.min' => 'Kategori minimal harus 3 karakter.',
            'class_level.required' => 'Tingkat kelas wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'price.min' => 'Harga buku minimal harus 3 digit.',
            'stock.required' => 'Stok buku wajib diisi.',
            'stock.min' => 'Stok buku tidak boleh kurang dari 0.',
            'desc.required' => 'Deskripsi buku wajib diisi.',
            'desc.min' => 'Deskripsi buku minimal harus 10 karakter.',
        ]);

        $book = Book::findOrFail($request->input('id'));
        $basePrice = (int) str_replace('.', '', $request->input('price'));
        
        $book->update([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'category' => $request->input('category'),
            'class' => 'Kelas ' . $request->input('class_level'),
            'base_price' => $basePrice,
            'price' => 'Rp ' . number_format($basePrice, 0, ',', '.'),
            'stock' => (int) $request->input('stock'),
            'desc' => $request->input('desc'),
            'image' => $request->filled('image') ? $request->input('image') : $book->image
        ]);
        
        return redirect()->back()->with('success', 'Data buku di database berhasil diperbarui!');
    }

    public function delete(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $book = Book::findOrFail($request->input('id'));
        $book->delete();
        
        return redirect()->back()->with('success', 'Buku telah dihapus dari database!');
    }
}
