<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $sizes = [
            ['name' => 'xl'],
            ['name' => 'l'],
            ['name' => 'm'],
            ['name' => 'sm'],
            ['name' => 'xxl'],
        ];

        $colors = [
            [
                'english_name' => 'Red',
                'myanmar_name' => 'အနီ',
            ],
            [
                'english_name' => 'White',
                'myanmar_name' => 'အဖြူ',
            ],
            [
                'english_name' => 'Black',
                'myanmar_name' => 'အနက်',
            ],
            [
                'english_name' => 'Blue',
                'myanmar_name' => 'အပြာ',
            ],
        ];

        ProductSize::insert($sizes);

        ProductColor::insert($colors);

        $brands = [
            [
                'name' => 'Brand A',
            ],
            [
                'name' => 'Brand B',
            ],
            [
                'name' => 'Brand C',
            ],
            [
                'name' => 'Brand D',
            ],
        ];
        $categories = [
            [
                'name' => 'Category A',
            ],
            [
                'name' => 'Category B',
            ],
            [
                'name' => 'Category C',

            ],
            [
                'name' => 'Category D',
            ],
        ];

        Brand::insert($brands);
        Category::insert($categories);
    }
}
