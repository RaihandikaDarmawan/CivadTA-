<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Book;

class BookController extends Controller
{
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('pelanggan.detail', ['book' => $book]);
    }
}
