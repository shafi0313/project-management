<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'DPS',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'DDPS',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'SOPS',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'SO(STAT)',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
        ];
        Section::insert($sections);
    }
}
