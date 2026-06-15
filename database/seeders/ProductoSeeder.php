<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Producto::create([
            'nombre' => 'Laptop Lenovo',
            'descripcion' => 'Laptop Ryzen 7',
            'precio' => 15000,
            'stock' => 10
        ]);

        \App\Models\Producto::create([
            'nombre' => 'Mouse Logitech',
            'descripcion' => 'Mouse inalámbrico',
            'precio' => 450,
            'stock' => 50
        ]);

        \App\Models\Producto::create([
            'nombre' => 'Teclado Mecánico',
            'descripcion' => 'RGB Switch Blue',
            'precio' => 1200,
            'stock' => 20
        ]);
    }
}
