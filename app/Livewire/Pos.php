<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pos extends Component
{
    public string $search = '';
    public string $customer = '';
    /** @var array<int, array{id:int,sku:string,name:string,price:float,qty:int}> */
    public array $cart = [];

    public float $subtotal = 0;
    public float $discount = 0;
    public float $tax = 0;
    public float $total = 0;

    public function updatedCart(): void
    {
        $this->recalc();
    }

    public function searchProducts(): void
    {
        $term = trim($this->search);
        if ($term === '') {
            return;
        }

        $query = Product::query()->select(['id','name','sku','barcode','unit_price as price','image_url']);
        if (preg_match('/^\d{6,}$/', $term)) {
            $query->where('barcode', $term);
        } else {
            $query->where(function ($w) use ($term) {
                $w->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%")
                  ->orWhere('barcode', 'like', "%{$term}%");
            });
        }

        $p = $query->first();
        if ($p) {
            $this->addToCart((int) $p->id, (string) $p->sku, (string) $p->name, (float) $p->price, (string) ($p->image_url ?? ''));
        }
    }

    public function addToCart(int $id, string $sku, string $name, float $price, string $imageUrl = ''): void
    {
        foreach ($this->cart as &$item) {
            if ($item['id'] === $id) {
                $item['qty'] += 1;
                $this->recalc();
                return;
            }
        }

        $this->cart[] = [
            'id' => $id,
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'image_url' => $imageUrl,
            'qty' => 1,
        ];

        $this->recalc();
    }

    public function remove(int $index): void
    {
        if (! array_key_exists($index, $this->cart)) return;
        array_splice($this->cart, $index, 1);
        $this->recalc();
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->recalc();
    }

    public function updateQty(int $index, int $qty): void
    {
        if (! array_key_exists($index, $this->cart)) return;
        $this->cart[$index]['qty'] = max(1, $qty);
        $this->recalc();
    }

    private function recalc(): void
    {
        $this->subtotal = collect($this->cart)->reduce(function ($sum, $i) {
            return $sum + ((float) $i['price'] * (int) $i['qty']);
        }, 0.0);
        $this->tax = 0.0;
        $this->total = $this->subtotal - $this->discount + $this->tax;
    }

    public function checkout(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('toast', message: 'Cart is empty');
            return;
        }

        DB::beginTransaction();
        try {
            $userId = Auth::id();

            $customerId = null;
            $customerInfo = null;
            $name = trim($this->customer);
            if ($name !== '') {
                $customer = Customer::query()->firstOrCreate(['name' => $name]);
                $customerId = $customer->id;
                $customerInfo = $name;
            }

            $reference = 'S' . now()->format('YmdHis');
            $sale = Sale::create([
                'customer_id' => $customerId,
                'user_id' => $userId,
                'reference' => $reference,
                'total_amount' => 0,
                'status' => 'completed',
                'sale_date' => now(),
                'customer_info' => $customerInfo,
            ]);

            $runningTotal = 0.0;

            foreach ($this->cart as $cartItem) {
                $product = Product::lockForUpdate()->find($cartItem['id']);
                if (! $product) {
                    throw new \RuntimeException('Product not found.');
                }

                $qty = (int) $cartItem['qty'];
                if ($qty < 1) {
                    throw new \RuntimeException('Invalid quantity.');
                }

                $available = (int) $product->quantity_available;
                if ($available < $qty) {
                    throw new \RuntimeException("Insufficient stock for {$product->name}. Available: {$available}");
                }

                $price = (float) $cartItem['price'];
                $lineTotal = $price * $qty;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'selling_price' => $price,
                    'subtotal' => $lineTotal,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $userId,
                    'type' => 'out',
                    'quantity' => $qty,
                    'cost_price' => $product->cost_price ?? null,
                    'selling_price' => $price,
                    'warehouse_id' => $product->warehouse_id,
                    'movement_date' => now(),
                    'note' => 'POS Sale ' . $reference,
                ]);

                $runningTotal += $lineTotal;
            }

            $sale->update(['total_amount' => $runningTotal]);

            DB::commit();

            $this->dispatch('toast', message: 'Checkout successful: $' . number_format($runningTotal, 2));
            $this->clearCart();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('toast', message: 'Checkout failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pos');
    }
}


