<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Processors', 'description' => 'Central Processing Units (CPUs)', 'slug' => 'processors'],
            ['name' => 'Graphics Cards', 'description' => 'Graphics Processing Units (GPUs)', 'slug' => 'graphics-cards'],
            ['name' => 'Memory', 'description' => 'RAM Modules', 'slug' => 'memory'],
            ['name' => 'Storage', 'description' => 'SSDs and HDDs', 'slug' => 'storage'],
            ['name' => 'Motherboards', 'description' => 'Main system boards', 'slug' => 'motherboards'],
            ['name' => 'Power Supplies', 'description' => 'PSUs', 'slug' => 'power-supplies'],
            ['name' => 'Cases', 'description' => 'Computer cases', 'slug' => 'cases'],
            ['name' => 'Cooling', 'description' => 'Fans and cooling systems', 'slug' => 'cooling'],
            ['name' => 'Accessories', 'description' => 'Cables, adapters, etc.', 'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
