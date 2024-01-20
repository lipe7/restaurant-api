<?php

namespace Database\Seeders;

use App\Models\Dish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $dishes = ['Feijoada', 'Moqueca', 'Acarajé', 'Bobó de Camarão', 'Picanha'];

        foreach ($dishes as $dish) {
            Dish::create([
                'name' => $dish,
                'price' => rand(2500, 5000) / 100
            ]);
        }
    }
}
