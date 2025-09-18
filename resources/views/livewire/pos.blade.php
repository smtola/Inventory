<div>
    <div class="container">
        <header class="flex items-center justify-between py-4">
            <h1 class="text-xl font-semibold">Point of Sale</h1>
            <div class="text-sm text-gray-500">{{ now()->format('Y-m-d H:i') }}</div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <section class="md:col-span-7 p-4 rounded-xl border bg-white dark:bg-gray-900">
                <div class="flex gap-2">
                    <input wire:model.defer="search" wire:keydown.enter.prevent="searchProducts" type="text" placeholder="Scan barcode or search products" class="w-full rounded-lg border px-3 py-2 dark:bg-gray-100">
                    <button wire:click="searchProducts" class="px-4 py-2 rounded-lg bg-primary-600 text-white  cursor-pointer">Search</button>
                </div>

                <div class="mt-4 max-h-[50vh] overflow-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-gray-500 dark:text-gray-100">
                            <tr>
                                <th class="py-2">Item</th>
                                <th class="py-2 w-24">Qty</th>
                                <th class="py-2 w-28">Price</th>
                                <th class="py-2 w-28">Total</th>
                                <th class="py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $index => $item)
                                <tr class="border-t dark:text-gray-100">
                                    <td class="py-2">
                                        <div class="flex items-center gap-2">
                                            @if(!empty($item['image_url']))
                                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-10 h-10 rounded object-cover">
                                            @endif
                                            <div>
                                                <div class="font-medium">{{ $item['name'] }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-100">{{ $item['sku'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="w-20 rounded border px-2 py-1" min="1" wire:change="updateQty({{ $index }}, $event.target.value)" value="{{ $item['qty'] }}">
                                    </td>
                                    <td><span>{{ number_format($item['price'], 2) }}</span></td>
                                    <td><span>{{ number_format($item['qty'] * $item['price'], 2) }}</span></td>
                                    <td>
                                        <button wire:click="remove({{ $index }})" class="text-red-600 hover:underline">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <aside class="md:col-span-5 p-4 rounded-xl border bg-white space-y-4">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format($subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span>Discount</span><span>{{ number_format($discount, 2) }}</span></div>
                    <div class="flex justify-between"><span>Tax</span><span>{{ number_format($tax, 2) }}</span></div>
                    <div class="flex justify-between font-semibold text-lg"><span>Total</span><span>{{ number_format($total, 2) }}</span></div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm">Customer</label>
                    <input type="text" class="w-full rounded border px-3 py-2" placeholder="Walk-in customer" wire:model.defer="customer">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="clearCart" class="px-4 py-2 rounded-lg border">Clear</button>
                    <button wire:click="checkout" class="px-4 py-2 rounded-lg bg-emerald-600 text-white">Checkout</button>
                </div>
            </aside>
        </div>
    </div>
</div>


