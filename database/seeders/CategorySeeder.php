<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['laptop', 'headphone', 'watch', 'accessory', 'phone'];

        for ($i = 0; $i < count($names); $i++) {
            DB::table('categories')->insert([
                'name' => $names[$i],
            ]);
        }
    }
}
