<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function readAllCustomer()
    {
        Notification::where('user_id', Auth::id())->where('role', 'pelanggan')->update(['is_read' => true]);
        return back();
    }

    public function readAllAdmin()
    {
        Notification::where('role', 'admin')->update(['is_read' => true]);
        return back();
    }
}
