<?php

namespace Database\Seeders;

use App\Models\SubSection;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subSections = [
            [
                'section_id' => 1,
                'name' => 'SD SECTION',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 1,
                'name' => 'HONORARY COMMISSION',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 1,
                'name' => 'SAILOR RECRUIT',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 1,
                'name' => 'ADMIN WORK',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 1,
                'name' => 'FREEDOM FIGHTER',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 1,
                'name' => 'MEDAL',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'OFFICE CREDIT',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'DEO',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'ADMISSION CAMPAIGN',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'INSIGNIA',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 3,
                'name' => 'CASH SECTION',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
        ];
        SubSection::insert($subSections);
    }
}
