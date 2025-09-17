<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SendLowStockAlerts extends Command
{
    protected $signature = 'alerts:low-stock {--threshold=5}';
    protected $description = 'Send low stock alerts for all products at or below the threshold';

    public function handle(): int
    {
        $threshold = (int) $this->option('threshold');

        $products = Product::query()
            ->where('quantity_available', '<=', $threshold)
            ->get();

        if ($products->isEmpty()) {
            $this->info('No products at or below the threshold.');
            return self::SUCCESS;
        }

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
        if ($admins->isEmpty()) {
            $this->warn('No admin users found to notify.');
            return self::SUCCESS;
        }

        foreach ($products as $product) {
            NotificationFacade::send($admins, new LowStockNotification(
                productId: $product->id,
                productName: $product->name,
                quantityAvailable: (int) $product->quantity_available,
                threshold: $threshold,
            ));
        }

        $this->info('Low stock alerts sent for '.$products->count().' products.');
        return self::SUCCESS;
    }
}


