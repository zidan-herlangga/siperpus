<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Karya fiksi, novel, cerita pendek, dll.'],
            ['name' => 'Non-Fiksi', 'description' => 'Karya non-fiksi, biografi, esai, dll.'],
            ['name' => 'Sains', 'description' => 'Buku ilmiah dan pengetahuan alam.'],
            ['name' => 'Matematika', 'description' => 'Buku matematika dan statistika.'],
            ['name' => 'Sejarah', 'description' => 'Buku sejarah dan peradaban.'],
            ['name' => 'Bahasa', 'description' => 'Buku bahasa, linguistik, dan kamus.'],
            ['name' => 'Seni', 'description' => 'Buku seni, musik, dan budaya.'],
            ['name' => 'Teknologi', 'description' => 'Buku teknologi, komputer, dan rekayasa.'],
            ['name' => 'Agama', 'description' => 'Buku agama dan spiritualitas.'],
            ['name' => 'Olahraga', 'description' => 'Buku olahraga dan kesehatan.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
