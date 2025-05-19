<?php
// app/Models/PurchaseOrder.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'product_id',
        'po_number',
        'order_date',
        'expected_delivery_date',
        'status',
        'notes',
        'quantity',
        'total_amount',
        'approved_by',
        'approved_at'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    // Add this date casting
    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    protected $with = ['items']; // Always load items relationship

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getTotalAmountAttribute()
    {
        return $this->quantity * ($this->product->cost_price ?? 0);
    }
}