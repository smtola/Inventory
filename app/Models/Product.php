<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'category_id', 'brand', 'unit_price', 'cost_price', 
        'quantity_available', 'warehouse_id', 'barcode', 'expiry_date'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products');
    }
}
