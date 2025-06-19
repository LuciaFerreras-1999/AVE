<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()

    {
        Categoria::insert([
            ['nombre' => 'Casual', 'descripcion' => 'Prendas de estilo casual', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Elegante', 'descripcion' => 'Prendas para ocasiones elegantes', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Deportivo', 'descripcion' => 'Prendas deportivas y cómodas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Fiesta', 'descripcion' => 'Prendas para eventos y celebraciones', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Verano', 'descripcion' => 'Ropa fresca para clima cálido', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Invierno', 'descripcion' => 'Prendas abrigadas para el frío', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Accesorios', 'descripcion' => 'Complementos para cualquier atuendo', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Calzado', 'descripcion' => 'Zapatos, sandalias y más', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa Interior', 'descripcion' => 'Prendas íntimas para uso diario', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Abrigos', 'descripcion' => 'Chaquetas y abrigos para cualquier ocasión', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pijamas', 'descripcion' => 'Ropa cómoda para dormir', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Trajes', 'descripcion' => 'Trajes formales para hombre y mujer', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Uniformes', 'descripcion' => 'Ropa específica para trabajo o estudio', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Vestidos', 'descripcion' => 'Prendas femeninas de una sola pieza', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Trabajo', 'descripcion' => 'Prendas resistentes para el ámbito laboral', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Bebé', 'descripcion' => 'Ropa suave y segura para bebés', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Niños', 'descripcion' => 'Prendas prácticas para niños y niñas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Mujer', 'descripcion' => 'Moda femenina para diversas ocasiones', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa de Hombre', 'descripcion' => 'Moda masculina para diversas ocasiones', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ropa Unisex', 'descripcion' => 'Prendas diseñadas para todos', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Trajes de Baño', 'descripcion' => 'Ropa para nadar y actividades acuáticas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
