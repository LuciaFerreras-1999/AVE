<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstiloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Definimos una lista de estilos
        $estilos = [
            'Casual',
            'Elegante',
            'Deportivo',
            'Vintage',
            'Minimalista',
            'Boho',
            'Clásico',
            'Streetwear',
            'Punk',
            'Gótico',
            'Rockero',
            'Preppy',
            'Chic',
            'Ecléctico',
            'Artsy',
            'Sofisticado',
            'Romántico',
            'Urbano',
            'Comodidad',
            'De oficina',
        ];

    }
}