<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // สร้าง root categories
        for ($i = 1; $i <= 5; $i++) {
            Category::create([
                'category_name' => "Root Category $i",
            ]);
        }

        // สร้าง sub-categories
        $categories = Category::all();
        foreach ($categories as $category) {
            for ($i = 1; $i <= 3; $i++) {
                Category::create([
                    'category_name' => "Sub Category $i of " . $category->category_name,
                    'parent_id' => $category->id,
                ]);
            }
        }

        // สร้าง sub-sub-categories
        $subCategories = Category::whereNotNull('parent_id')->get();
        foreach ($subCategories as $subCategory) {
            for ($i = 1; $i <= 2; $i++) {
                Category::create([
                    'category_name' => "Sub-Sub Category $i of " . $subCategory->category_name,
                    'parent_id' => $subCategory->id,
                ]);
            }
        }
    }
}
