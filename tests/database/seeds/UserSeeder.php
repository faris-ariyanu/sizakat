<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'developer@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('123456'),
                'status' => 1,
        		'role' => 1,
            ]
        );
    }
}
