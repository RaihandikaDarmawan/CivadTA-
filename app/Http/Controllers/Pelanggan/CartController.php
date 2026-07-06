<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class CartController extends Controller
{
    public function index()
    {
        return view('pelanggan.keranjang');
    }

    public function add(Request $request)
    {
        $book = Book::findOrFail($request->input('buku_id'));
        $qtyToAdd = (int) $request->input('qty', 1);

        $cart = session('cart', []);
        if (isset($cart[$book->id])) {
            $cart[$book->id]['qty'] += $qtyToAdd;
        } else {
            $cart[$book->id] = [
                'id' => $book->id,
                'title' => $book->title,
                'price' => $book->base_price,
                'category' => $book->category,
                'class' => $book->class,
                'qty' => $qtyToAdd
            ];
        }
        session(['cart' => $cart]);
        session(['cart_count' => count($cart)]);
        
        return redirect()->back()->with('success', $book->title . ' berhasil ditambahkan ke keranjang!');
    }

    public function buyNow(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect('/pelanggan/dashboard');
        }
        $book = Book::findOrFail($request->input('buku_id'));
        $qtyToAdd = (int) $request->input('qty', 1);

        $cart = session('cart', []);
        if (isset($cart[$book->id])) {
            $cart[$book->id]['qty'] += $qtyToAdd;
        } else {
            $cart[$book->id] = [
                'id' => $book->id,
                'title' => $book->title,
                'price' => $book->base_price,
                'category' => $book->category,
                'class' => $book->class,
                'qty' => $qtyToAdd
            ];
        }
        session(['cart' => $cart]);
        session(['cart_count' => count($cart)]);
        
        return redirect('/pelanggan/pesanan');
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        $id = $request->input('id');
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
            session(['cart_count' => count($cart)]);
        }
        
        return redirect()->back()->with('success', 'Buku berhasil dihapus dari keranjang!');
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        $id = $request->input('id');
        $qty = (int) $request->input('qty');
        
        if (isset($cart[$id]) && $qty > 0) {
            $cart[$id]['qty'] = $qty;
            session(['cart' => $cart]);
            session(['cart_count' => count($cart)]);
        }
        
        return response()->json(['success' => true]);
    }
}
