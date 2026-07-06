<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Notification;

class OrderController extends Controller
{
    public function index()
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        Order::autoCompleteOldOrders();
        
        $orders = Order::with(['user', 'items.book'])->orderBy('created_at', 'desc')->get();
        
        return view('admin.pesanan', [
            'orders' => $orders
        ]);
    }

    public function updateStatus(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $order = Order::findOrFail($request->input('id'));
        
        if ($order->status === 'Pengembalian Ditolak') {
            return redirect()->back()->with('error', 'Pesanan dengan status Pengembalian Ditolak tidak dapat diubah statusnya!');
        }
        
        $newStatus = $request->input('status');
        if ($newStatus === 'Sedang Dikirim' && !$request->filled('tracking_link')) {
            return redirect()->back()->with('error', 'Link perjalanan pelacakan (tracking link) wajib diisi sebelum mengubah status menjadi Sedang Dikirim!');
        }
        
        $order->status = $newStatus;
        
        if ($request->filled('rejection_reason')) {
            $order->rejection_reason = $request->input('rejection_reason');
        }
        
        if ($request->has('tracking_link')) {
            $order->tracking_link = $request->input('tracking_link');
        }
        
        if ($request->input('status') === 'Selesai' && !$order->points_awarded) {
            $user = $order->user;
            if ($user) {
                $pointsEarned = floor($order->total_amount / 10000);
                $user->increment('points', $pointsEarned);
                $order->points_awarded = true;
            }
        }

        $order->save();
        
        // Notify Customer about status update
        Notification::send('pelanggan', 'Status Pesanan Diperbarui', 'Pesanan #' . $order->order_number . ' Anda kini berstatus: ' . $order->status, $order->user_id, 'info', '/pelanggan/riwayat');
        
        return redirect()->back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diubah menjadi ' . $order->status);
    }

    public function salesReport(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $filter = $request->input('filter', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = Order::where('status', 'Selesai')->with(['user', 'items.book'])->orderBy('created_at', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $filter = 'custom';
        } else {
            if ($filter === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($filter === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter === 'month') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($filter === 'year') {
                $query->whereYear('created_at', now()->year);
            }
        }

        $orders = $query->get();
        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $totalBooks = $orders->flatMap->items->sum('quantity');

        return view('admin.laporan', [
            'orders' => $orders,
            'filter' => $filter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalBooks' => $totalBooks
        ]);
    }

    public function exportReport(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses ditolak!');
        
        $filter = $request->input('filter', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = Order::where('status', 'Selesai')->with(['user', 'items.book'])->orderBy('created_at', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $periodLabel = "Custom ($startDate s/d $endDate)";
        } else {
            if ($filter === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($filter === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter === 'month') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($filter === 'year') {
                $query->whereYear('created_at', now()->year);
            }
            $periodLabel = strtoupper($filter);
        }

        $orders = $query->get();
        $totalRevenue = $orders->sum('total_amount');
        
        $filename = "Laporan_Penjualan_CIVAD_" . date('Y-m-d') . ".csv";
        
        header("Content-Type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel support
        fwrite($output, "\xEF\xBB\xBF");
        
        // Tell Excel to use comma as separator
        fwrite($output, "sep=,\r\n");

        fputcsv($output, ["LAPORAN PENJUALAN CIVAD"]);
        fputcsv($output, ["Periode: " . $periodLabel]);
        fputcsv($output, []); // Empty spacer line

        // Header row
        fputcsv($output, ["No. Pesanan", "Tanggal", "Pelanggan", "Total", "Status"]);

        // Data rows
        foreach($orders as $order) {
            fputcsv($output, [
                "#" . $order->order_number,
                $order->created_at->format('d/m/Y H:i'),
                $order->user->name ?? 'Guest',
                "Rp " . number_format($order->total_amount, 0, ',', '.'),
                $order->status
            ]);
        }

        fputcsv($output, []); // Empty spacer line
        fputcsv($output, ["TOTAL PENDAPATAN", "", "", "Rp " . number_format($totalRevenue, 0, ',', '.')]);

        fclose($output);
        exit;
    }
}
