<?php

// database/seeders/ProductsTableSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
        $products = [
            [
                'name' => 'Intel Core i9-12900K',
                'description' => '16-core, 24-thread unlocked desktop processor',
                'price' => 599.99,
                'category_id' => $categories->where('name', 'Processors')->first()->id,
                'sku' => 'CPU-INT-12900K',
                'image' => 'images/OIP (5).jpg',

                'is_featured' => true
            ],
            [
                'name' => 'AMD Ryzen 9 5950X',
                'description' => '16-core, 32-thread unlocked desktop processor',
                'price' => 549.99,
                'category_id' => $categories->where('name', 'Processors')->first()->id,
                'sku' => 'CPU-AMD-5950X',
                'image' => null,

                'is_featured' => true
            ],
            [
                'name' => 'NVIDIA GeForce RTX 3090',
                'description' => '24GB GDDR6X graphics card',
                'price' => 1499.99,
                'category_id' => $categories->where('name', 'Graphics Cards')->first()->id,
                'sku' => 'GPU-NV-3090',
                'image' => null,

                'is_featured' => true
            ],
            [
                'name' => 'AMD Radeon RX 6900 XT',
                'description' => '16GB GDDR6 graphics card',
                'price' => 999.99,
                'category_id' => $categories->where('name', 'Graphics Cards')->first()->id,
                'sku' => 'GPU-AMD-6900XT',
                'image' => null,

                'is_featured' => true
            ],
            [
                'name' => 'Corsair Vengeance RGB Pro 32GB',
                'description' => 'DDR4 3600MHz memory kit (2x16GB)',
                'price' => 169.99,
                'category_id' => $categories->where('name', 'Memory')->first()->id,
                'sku' => 'MEM-COR-32RGB',
                'image' => null,
                'is_featured' => true
            ],
          
        ];
         
         
         
         
         
         
        
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}