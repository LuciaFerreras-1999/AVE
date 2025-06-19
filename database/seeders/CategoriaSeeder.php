<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()

    {
        Categoria::insert([
            ['nombre' => 'Parte Superior', 'descripcion' => 'Camisetas, camisas, blusas, etc.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Parte Inferior', 'descripcion' => 'Pantalones, faldas, shorts, etc.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Chaquetas y Abrigos', 'descripcion' => 'Prendas para clima frío', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Calzado', 'descripcion' => 'Zapatos, sandalias, botas, etc.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa Interior', 'descripcion' => 'Prendas íntimas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pijamas', 'descripcion' => 'Ropa para dormir', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Trajes', 'descripcion' => 'Trajes formales y de negocios', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa Deportiva', 'descripcion' => 'Ropa para actividad física', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Baño', 'descripcion' => 'Bikinis, bañadores, etc.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Accesorios', 'descripcion' => 'Gorros, bufandas, bolsos, etc.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Bebé', 'descripcion' => 'Prendas para recién nacidos y bebés', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Niños', 'descripcion' => 'Ropa para niños y niñas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Hombre', 'descripcion' => 'Moda masculina', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Mujer', 'descripcion' => 'Moda femenina', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa Unisex', 'descripcion' => 'Prendas para todos los géneros', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Uniformes y Trabajo', 'descripcion' => 'Ropa laboral o escolar', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Verano', 'descripcion' => 'Ropa fresca para el calor', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Invierno', 'descripcion' => 'Ropa abrigada para el frío', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Entretiempo', 'descripcion' => 'Prendas ideales para primavera y otoño', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Vestidos', 'descripcion' => 'Vestidos de todo tipo y ocasión', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
