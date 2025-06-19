<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prenda;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Str;

class PrendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $categorias = Categoria::all();

        $prendas = [
            [
                'nombre' => 'Camiseta Casual',
                'descripcion' => 'Camiseta de algodón para uso diario.',
                'talla' => 'M',
                'marca' => 'MarcaX',
                'estado' => 'nuevo',
                'imagen' => 'camiseta.jpg',
            ],
            [
                'nombre' => 'Vestido Elegante',
                'descripcion' => 'Vestido de seda perfecto para eventos formales.',
                'talla' => 'S',
                'marca' => 'MarcaY',
                'estado' => 'usado',
                'imagen' => 'vestido.jpg',
            ],
            [
                'nombre' => 'Chaqueta Deportiva',
                'descripcion' => 'Chaqueta impermeable para actividades al aire libre.',
                'talla' => 'L',
                'marca' => 'MarcaZ',
                'estado' => 'nuevo',
                'imagen' => 'chaquetaDeportiva.jpg',
            ],
        ];

        foreach ($prendas as $prendaData) {
            $prenda = Prenda::create(array_merge($prendaData, [
                'user_id' => $user->id,
                'slug' => Str::slug($prendaData['nombre']),
            ]));

            $randomCategorias = $categorias->random(rand(1, 3))->pluck('id');
            $prenda->categorias()->sync($randomCategorias);
        }
    }
}
