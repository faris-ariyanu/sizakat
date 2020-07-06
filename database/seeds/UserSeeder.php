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
            ['username' => 'sadmin'],
            [
                'code' => 'ADM000001',
                'name' => 'Super Admin',
                'email' => 'sadmin@mail.com',
                'password' => bcrypt('123456'),
                'status' => 1,
        		'role' => 1,
            ]
        );
    }
}
