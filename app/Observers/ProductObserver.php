<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use Filament\Notifications\Notification;
use App\Filament\Resources\ProductResource;
use Filament\Notifications\Actions\Action;

class ProductObserver
{
    /**
     * Handle the Product "created" event. 
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $recipient = auth()->user();
        if (! $recipient) {
            return;
        }

        Notification::make()
            ->title('Product created successfully')
            ->body("Product {$product->name} has been created.")
            ->success()
            ->sendToDatabase($recipient);
           
        // Only check if quantity_available changed
        if ($product->wasChanged('quantity_available')) {
            $newQty = (int) $product->quantity_available;
            $oldQty = (int) $product->getOriginal('quantity_available');
            $threshold = 5;

            // Notify when crossing from at/above threshold to below threshold
            if ($oldQty >= $threshold && $newQty < $threshold) {
                $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
                if ($admins->isEmpty() && auth()->check()) {
                    // Fallback to current user if no admins found
                    $admins = collect([auth()->user()]);
                }
                
                if ($admins->isNotEmpty()) {
                    foreach ($admins as $admin) {
                        Notification::make()
                        ->title('Low stock alert')
                        ->body(sprintf(
                                'Product %s is low on stock: %d remaining (threshold %d).',
                                $product->name,
                                $newQty,
                                $threshold
                            ))
                        ->danger()
                        ->sendToDatabase($admin);
                    }
                }
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
