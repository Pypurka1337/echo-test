<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(1)->create();
        Category::factory(2)->create([
            'parent_id' => 1,
        ]);
        Category::factory(3)->create([
            'parent_id' => 3,
        ]);
        Category::factory(3)->create([
            'parent_id' => 4,
        ]);
    }
}
