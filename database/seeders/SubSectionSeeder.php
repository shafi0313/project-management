<?php

namespace Database\Seeders;

use App\Models\SubSection;
use Illuminate\Database\Seeder;

class SubSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subSections = [
            [
                'section_id' => 2,
                'name' => 'SD SECTION',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'HONORARY COMMISSION',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'SAILOR RECRUIT',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'ADMIN WORK',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'FREEDOM FIGHTER',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 2,
                'name' => 'MEDAL',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 3,
                'name' => 'OFFICE CREDIT',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 3,
                'name' => 'DEO',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 3,
                'name' => 'ADMISSION CAMPAIGN',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 3,
                'name' => 'INSIGNIA',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
            [
                'section_id' => 4,
                'name' => 'CASH SECTION',
                'is_active' => 1,
                //'removable' => 0,
                'created_at' => now(),
            ],
        ];
        SubSection::insert($subSections);
    }
}
