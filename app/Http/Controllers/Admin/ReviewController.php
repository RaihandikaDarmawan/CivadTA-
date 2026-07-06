<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $reviews = Review::with(['order', 'user'])->orderBy('created_at', 'desc')->get();
        
        return view('admin.ulasan', ['reviews' => $reviews]);
    }

    public function delete(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $review = Review::findOrFail($request->input('id'));
        $review->delete();
        
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
