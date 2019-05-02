<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Обувь',
                'sort' => 100,
            ],
            [
                'name' => 'Одежда',
                'sort' => 200
            ],
            [
                'name' => 'Аксессуары',
                'sort' => 300
            ]
        ];
        foreach($categories as $categoryData){
            \App\Models\Category::create($categoryData);
        }
    }
}
