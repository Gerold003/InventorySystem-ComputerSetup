<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Product;
use App\Models\DepartmentItem;
use Illuminate\Database\Seeder;

class DepartmentItemsSeeder extends Seeder
{
    public function run()
    {
        // Get all departments and products
        $departments = Department::all();
        $products = Product::all();

        // Assign some random products to each department
        foreach ($departments as $department) {
            // Assign 2-5 random products to each department
            $randomProducts = $products->random(rand(2, 5));
            
            foreach ($randomProducts as $product) {
                DepartmentItem::create([
                    'department_id' => $department->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 20)
                ]);
            }
        }
    }
} 