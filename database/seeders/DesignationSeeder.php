<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'name' => 'DPS',
                'is_active' => 1,
            ],
            [
                'name' => 'DDPS',
                'is_active' => 1,
            ],
            [
                'name' => 'SOPS',
                'is_active' => 1,
            ],
            [
                'name' => 'SO(STAT)',
                'is_active' => 1,
            ],
        ];
        Designation::insert($designations);
    }
}
