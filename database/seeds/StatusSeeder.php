<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::firstOrCreate(
            ['type' => 'global','code' => 0],
            [
                'name' => 'Tidak Aktif',
                'class' => 'danger'
            ]
        );

        Status::firstOrCreate(
            ['type' => 'global','code' => 1],
            [
                'name' => 'Aktif',
                'class' => 'success'
            ]
        );
    }
}
