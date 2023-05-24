<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryNames = Category::query()->pluck('name')->toArray();

        for ($i = 0; $i < 5; $i++) {
            $randCategoryName = $categoryNames[array_rand($categoryNames)];
            $productName = $randCategoryName . '_' . $i;

            $categoryId = Category::query()->where('name', $randCategoryName)->value('id');

            DB::table('products')->insert([
                'name' => $productName,
                'description' => 'Lorem ipsum dolor sit',
                'price' => rand(100, 10000),
                'count' => rand(0, 300),
                'category_id' => $categoryId,
            ]);
        }
    }
}
