<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert[] = [
    		"id" => "1",
    		"name" => "Manajemen Pengguna",
    		"parent" => "0",
    		"icon" => "fa fa-user",
    		"link" => "#",
    		"sequence" => 20,
			"action" => json_encode(["store","update","delete"])
    	];
    	$insert[] = [
    		"id" => "2",
    		"name" => "Role",
    		"parent" => "1",
    		"icon" => "fa fa-list",
    		"link" => "/role",
    		"sequence" => 0,
			"action" => json_encode(["store","update","delete"])
    	];

    	$insert[] = [
    		"id" => "3",
    		"name" => "Pengguna",
    		"parent" => "1",
    		"icon" => "fa fa-list",
    		"link" => "/user",
    		"sequence" => 1,
			"action" => json_encode(["store","update","delete"])
    	];

    	$insert[] = [
    		"id" => "4",
    		"name" => "Menu",
    		"parent" => "1",
    		"icon" => "fa fa-list",
    		"link" => "/menu",
    		"sequence" => 2,
			"action" => json_encode(["store","update","delete"])
		];
		

		$insert[] = [
    		"id" => "5",
    		"name" => "Dashboard",
    		"parent" => "0",
    		"icon" => "fa fa-home",
    		"link" => "/dashboard",
			"sequence" => 0,
			"action" => json_encode(["read"])
    	];

        Menu::truncate();
        Menu::insert($insert);
    }
}
