<?php

namespace Database\Seeders;

use App\Constants\Gender;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Admin',
                'email' => 'admin@app.com',
                'user_name' => null,
                'role' => 1,
                'gender' => Gender::MALE,
                'section_id' => 1,
                'sub_section_id' => null,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin2@app.com',
                'user_name' => null,
                'role' => 1,
                'gender' => Gender::MALE,
                'section_id' => null,
                'sub_section_id' => 1,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
        ];

        foreach ($admins as $adminData) {
            $user = User::create($adminData);
            $user->assignRole(['superadmin']);
        }
    }
}
