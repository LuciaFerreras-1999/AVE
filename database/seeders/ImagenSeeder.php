<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('imagenes')->insert([
            [
                'user_id' => 1,
                'prenda_id' => 1,
                'ruta' => 'camiseta.jpg',
            ],
            [
                'user_id' => 1,
                'prenda_id' => 2,
                'ruta' => 'vestido.jpg',
            ],
            [
                'user_id' => 2,
                'prenda_id' => 3,
                'ruta' => 'chaquetaDeportiva.jpg',
            ],
        ]);
    }
}
