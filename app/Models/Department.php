<?php

// app/Models/Department.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'head_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function items()
    {
        return $this->hasMany(DepartmentItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            DepartmentItem::class,
            'department_id', // Foreign key on department_items table
            'id', // Local key on products table
            'id', // Local key on departments table
            'product_id' // Foreign key on department_items table
        );
    }
}