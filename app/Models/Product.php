<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'category_id',
        'supplier_id',
        'price',
        'image',
        'is_featured',
        'cost_price',
        'selling_price',
        'reorder_point',
        'reorder_quantity',
        'department_id'
    ];

    protected $attributes = [
        'is_featured' => false,
        'image' => null,
        'reorder_point' => 5,
        'reorder_quantity' => 10
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'reorder_point' => 'integer',
        'reorder_quantity' => 'integer',
        'is_featured' => 'boolean'
    ];

    protected $with = ['inventory']; // Always load inventory relationship

    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getCurrentStockAttribute()
    {
        return $this->inventory ? $this->inventory->quantity : 0;
    }

    public function ensureInventoryExists($attributes = [])
    {
        if (!$this->inventory) {
            return $this->inventory()->create(array_merge([
                'quantity' => 0,
                'low_stock_threshold' => $this->reorder_point
            ], $attributes));
        }
        
        $this->inventory->update($attributes);

        return $this->inventory;
    }

    public function getInventoryQuantityAttribute()
    {
        return $this->current_stock;
    }

    public function getLowStockThresholdAttribute()
    {
        return $this->inventory ? $this->inventory->low_stock_threshold : $this->reorder_point;
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->low_stock_threshold;
    }

    public function recordStockMovement($quantity, $type, $reason, $reference = null)
    {
        $movement = $this->stockMovements()->create([
            'quantity' => $quantity,
            'type' => $type,
            'reason' => $reason,
            'user_id' => auth()->id()
        ]);

        if ($reference) {
            $movement->reference()->associate($reference);
            $movement->save();
        }

        return $movement;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-image.png');
    }
}