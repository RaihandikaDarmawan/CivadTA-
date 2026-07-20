<?php
use App\Models\Order;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = Order::where('status', 'Selesai')->where('points_awarded', false)->get();
echo "Found " . $orders->count() . " orders to process.\n";

foreach ($orders as $order) {
    $user = $order->user;
    if ($user) {
        $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
        $pointsEarned = floor($subtotal / 10000);
        $user->increment('points', $pointsEarned);
        $order->points_awarded = true;
        $order->save();
        echo "Awarded $pointsEarned points to {$user->name} for order #{$order->order_number}\n";
    }
}
echo "Done.\n";
